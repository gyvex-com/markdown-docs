<?php

namespace GyvexCom\MarkdownDocs\Repositories;

use GyvexCom\MarkdownDocs\Contracts\MarkdownDocsRepository;
use GyvexCom\MarkdownDocs\Doc;
use GyvexCom\MarkdownDocs\Support\FrontMatter;
use GyvexCom\MarkdownDocs\Support\TreeBuilder;
use Illuminate\Support\Facades\Cache;
use League\CommonMark\GithubFlavoredMarkdownConverter;

class FilesystemRepository implements MarkdownDocsRepository
{
    protected GithubFlavoredMarkdownConverter $converter;

    public function __construct()
    {
        $this->converter = new GithubFlavoredMarkdownConverter([
            'html_input' => 'escape',
            'allow_unsafe_links' => false,
        ]);
    }

    public function resolve(string $path): ?Doc
    {
        $root = rtrim($this->docsPath(), '/');

        $path = trim($path, '/');

        if ($path === '') {
            $file = $root.'/'.$this->index().'.md';
        } else {
            $file = $root.'/'.$path.'.md';

            // A path segment that resolves to a directory uses its index file.
            if (is_dir($root.'/'.$path)) {
                $file = $root.'/'.$path.'/'.$this->index().'.md';
            }
        }

        $real = realpath($file);

        if ($real === false || !is_file($real)) {
            return null;
        }

        $realRoot = realpath($root);

        // Path-traversal guard: the resolved file must live inside the docs root.
        if ($realRoot === false || strncmp($real, $realRoot.DIRECTORY_SEPARATOR, strlen($realRoot.DIRECTORY_SEPARATOR)) !== 0) {
            return null;
        }

        $contents = file_get_contents($real);
        [$metadata, $body] = FrontMatter::parse($contents);

        $relativePath = ltrim(substr($real, strlen($realRoot)), '/');

        return new Doc(
            path: $real,
            relativePath: $relativePath,
            url: $this->urlFor($path),
            metadata: $metadata,
            body: $body,
            mtime: filemtime($real),
        );
    }

    public function render(Doc $doc): string
    {
        if (!config('docs.cache', true)) {
            return (string) $this->converter->convert($doc->body);
        }

        $key = 'markdown-docs:'.md5($doc->path).':'.$doc->mtime;

        return Cache::rememberForever($key, function () use ($doc) {
            return (string) $this->converter->convert($doc->body);
        });
    }

    public function tree(): array
    {
        return (new TreeBuilder())->build();
    }

    protected function docsPath(): string
    {
        return config('docs.path', base_path('docs'));
    }

    protected function index(): string
    {
        return config('docs.index', 'index');
    }

    protected function urlFor(string $path): string
    {
        $prefix = trim(config('docs.route_prefix', 'docs'), '/');

        if ($path === '') {
            return '/'.$prefix;
        }

        return '/'.$prefix.'/'.$path;
    }
}
