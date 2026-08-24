<?php

namespace GyvexCom\MarkdownDocs\Tests;

use GyvexCom\MarkdownDocs\Support\TreeBuilder;

class TreeBuilderTest extends PackageTestCase
{
    public function test_tree_builds_nested_and_orders(): void
    {
        $this->writeDoc('index.md', "---\ntitle: Home\norder: 1\n---\n# Home");
        $this->writeDoc('getting-started/index.md', "---\ntitle: Getting Started\norder: 2\n---\n# GS");
        $this->writeDoc('getting-started/install.md', "---\ntitle: Install\norder: 1\n---\n# Install");
        $this->writeDoc('getting-started/upgrade.md', "Upgrade");
        $this->writeDoc('zzz-last.md', "---\norder: 5\n---\n# Zzz");

        $tree = (new TreeBuilder())->build();

        // Home (order 1) then Getting Started (order 2) then zzz (order 5).
        $this->assertCount(3, $tree);
        $this->assertSame('Home', $tree[0]['title']);
        $this->assertSame('/docs', $tree[0]['url']);
        $this->assertSame('Getting Started', $tree[1]['title']);

        $gs = $tree[1];
        // The index becomes the directory node; install/upgrade are children.
        $this->assertSame('/docs/getting-started', $gs['url']);
        $this->assertCount(2, $gs['children']);

        // Children ordered by order then title: Install(1) before Upgrade(999).
        $this->assertSame('Install', $gs['children'][0]['title']);
        $this->assertSame('/docs/getting-started/install', $gs['children'][0]['url']);
        $this->assertSame('Upgrade', $gs['children'][1]['title']);
    }

    public function test_urls_never_include_md_extension(): void
    {
        $this->writeDoc('a/b.md', "# B");
        $tree = (new TreeBuilder())->build();

        $found = json_encode($tree);
        $this->assertStringNotContainsString('.md', $found);

        $leaf = $tree[0]['children'][0];
        $this->assertSame('/docs/a/b', $leaf['url']);
    }
}
