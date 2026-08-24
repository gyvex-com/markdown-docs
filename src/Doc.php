<?php

namespace GyvexCom\MarkdownDocs;

use Illuminate\Support\Str;

class Doc
{
    public function __construct(
        public string $path,
        public string $relativePath,
        public string $url,
        public array $metadata,
        public string $body,
        public int $mtime,
    ) {
    }

    public function title(): string
    {
        return $this->metadata['title']
            ?? Str::title(str_replace(['-', '_', '/'], ' ', $this->relativePath));
    }

    public function order(): int
    {
        return (int) ($this->metadata['order'] ?? 999);
    }
}
