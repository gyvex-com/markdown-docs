<?php

namespace GyvexCom\MarkdownDocs\Support;

use GyvexCom\MarkdownDocs\Doc;
use Symfony\Component\Finder\Finder;

class TreeBuilder
{
    /**
     * Build a nested navigation tree.
     *
     * Structure per node:
     *   ['title' => string, 'url' => string, 'order' => int, 'children' => array]
     *
     * Directory index files become the directory's own entry (no separate child),
     * so directory links stay clean. Remaining .md files become children.
     */
    public function build(?string $directory = null): array
    {
        $root = $this->docsPath();
        $scan = $directory === null ? $root : $directory;

        if (!is_dir($scan)) {
            return [];
        }

        $indexName = $this->index().'.md';
        $prefix = trim(config('docs.route_prefix', 'docs'), '/');

        $nodes = [];

        // Files (non-recursive in this directory level).
        $files = (new Finder())
            ->files()
            ->in($scan)
            ->depth('== 0')
            ->name('*.md')
            ->sortByName();

        foreach ($files as $file) {
            $base = $file->getBasename('.md');
            $relative = ltrim(substr($file->getPathname(), strlen($root)), '/');

            [$metadata] = FrontMatter::parse($file->getContents());

            $isIndex = $base === $this->index();

            // Build the URL: strip .md, and drop the index segment so directory
            // links point at the folder rather than /folder/index.
            $urlParts = [];
            if ($relative !== '') {
                $segments = explode('/', $relative);
                if ($isIndex) {
                    array_pop($segments); // remove index filename -> directory
                } else {
                    $segments[count($segments) - 1] = $base;
                }
                $urlParts = array_filter($segments, fn ($s) => $s !== '');
            }

            $url = '/'.$prefix;
            if (!empty($urlParts)) {
                $url .= '/'.implode('/', $urlParts);
            }

            $title = $metadata['title'] ?? (
                $isIndex
                    ? ($metadata['title'] ?? $this->dirTitle(dirname($relative) === '.' ? '' : dirname($relative)))
                    : $this->humanize($base)
            );

            $order = (int) ($metadata['order'] ?? ($isIndex ? -1 : 999));

            $nodes[$relative] = [
                'title' => $title,
                'url' => $url,
                'order' => $order,
                'children' => [],
                'is_index' => $isIndex,
            ];
        }

        // Directories (non-recursive), recursing into each.
        $dirs = (new Finder())
            ->directories()
            ->in($scan)
            ->depth('== 0')
            ->sortByName();

        foreach ($dirs as $dir) {
            $relative = ltrim(substr($dir->getPathname(), strlen($root)), '/');
            $children = $this->build($dir->getPathname());

            $indexNode = null;
            foreach ($children as $key => $child) {
                if ($child['is_index'] ?? false) {
                    $indexNode = $child;
                    unset($children[$key]);
                    break;
                }
            }

            $node = $indexNode ?? [
                'title' => $this->dirTitle($relative),
                'url' => '/'.$prefix.'/'.str_replace('/', '/', $relative),
                'order' => 999,
                'children' => [],
                'is_index' => false,
            ];

            $node['children'] = array_values($children);

            $nodes[$relative] = $node;
        }

        // Sort: index first, then by order, then by title.
        uasort($nodes, function ($a, $b) {
            if ($a['order'] !== $b['order']) {
                return $a['order'] <=> $b['order'];
            }

            return strnatcasecmp($a['title'], $b['title']);
        });

        return array_values($nodes);
    }

    protected function docsPath(): string
    {
        return rtrim(config('docs.path', base_path('docs')), '/');
    }

    protected function index(): string
    {
        return config('docs.index', 'index');
    }

    protected function humanize(string $name): string
    {
        return (string) \Illuminate\Support\Str::title(
            str_replace(['-', '_'], ' ', $name)
        );
    }

    protected function dirTitle(string $relative): string
    {
        $last = basename($relative);

        if ($relative === '' || $last === '') {
            return 'Introduction';
        }

        return $this->humanize($last);
    }
}
