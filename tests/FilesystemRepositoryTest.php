<?php

namespace GyvexCom\MarkdownDocs\Tests;

use GyvexCom\MarkdownDocs\Repositories\FilesystemRepository;

class FilesystemRepositoryTest extends PackageTestCase
{
    public function test_root_index_resolves(): void
    {
        $this->writeDoc('index.md', "---\ntitle: Welcome\n---\n# Welcome\nHello world");

        $repo = new FilesystemRepository();
        $doc = $repo->resolve('');

        $this->assertNotNull($doc);
        $this->assertStringContainsString('Hello world', $repo->render($doc));
        $this->assertSame('Welcome', $doc->title());
    }

    public function test_nested_path_resolves_and_strips_md(): void
    {
        $this->writeDoc('getting-started/install.md', "---\ntitle: Install\norder: 1\n---\n# Install\nSteps");

        $repo = new FilesystemRepository();
        $doc = $repo->resolve('getting-started/install');

        $this->assertNotNull($doc);
        $this->assertSame('/docs/getting-started/install', $doc->url);
        $this->assertStringContainsString('<h1', $repo->render($doc));
        // Front matter must be stripped from the rendered body.
        $this->assertStringNotContainsString('title: Install', $repo->render($doc));
    }

    public function test_directory_uses_index(): void
    {
        $this->writeDoc('guide/index.md', "# Guide Home");

        $repo = new FilesystemRepository();
        $doc = $repo->resolve('guide');

        $this->assertNotNull($doc);
        $this->assertSame('/docs/guide', $doc->url);
    }

    public function test_missing_file_returns_null(): void
    {
        $repo = new FilesystemRepository();
        $this->assertNull($repo->resolve('does-not-exist'));
    }

    public function test_path_traversal_is_blocked(): void
    {
        $this->writeDoc('index.md', "root");

        // Try to escape the docs root via traversal.
        $repo = new FilesystemRepository();
        $doc = $repo->resolve('../../../../../../etc/passwd');

        $this->assertNull($doc);
    }
}
