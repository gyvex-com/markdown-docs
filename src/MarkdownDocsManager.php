<?php

namespace GyvexCom\MarkdownDocs;

use GyvexCom\MarkdownDocs\Contracts\MarkdownDocsRepository;

class MarkdownDocsManager
{
    public function __construct(protected MarkdownDocsRepository $repository)
    {
    }

    public function resolve(string $path): ?Doc
    {
        return $this->repository->resolve($path);
    }

    public function render(Doc $doc): string
    {
        return $this->repository->render($doc);
    }

    public function tree(): array
    {
        return $this->repository->tree();
    }
}
