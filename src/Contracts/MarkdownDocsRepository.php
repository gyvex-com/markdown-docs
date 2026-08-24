<?php

namespace GyvexCom\MarkdownDocs\Contracts;

interface MarkdownDocsRepository
{
    /**
     * Resolve a request path to a Doc, or null when not found / not allowed.
     */
    public function resolve(string $path): ?\GyvexCom\MarkdownDocs\Doc;

    /**
     * Render a doc's markdown body (front matter stripped) to HTML.
     */
    public function render(\GyvexCom\MarkdownDocs\Doc $doc): string;

    /**
     * Build the nested navigation tree.
     */
    public function tree(): array;
}
