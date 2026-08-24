<?php

namespace GyvexCom\MarkdownDocs\Tests;

use GyvexCom\MarkdownDocs\MarkdownDocsServiceProvider;
use Illuminate\Support\Facades\Config;
use Orchestra\Testbench\TestCase;

class PackageTestCase extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [MarkdownDocsServiceProvider::class];
    }

    protected function setUp(): void
    {
        parent::setUp();

        $docs = sys_get_temp_dir().'/md-docs-'.uniqid();
        mkdir($docs, 0777, true);

        Config::set('docs.path', $docs);
        Config::set('docs.route_prefix', 'docs');
        Config::set('docs.cache', false);

        $this->docsPath = $docs;
    }

    protected function tearDown(): void
    {
        $this->removeDir($this->docsPath);
        parent::tearDown();
    }

    protected function writeConfig(string $content): void
    {
        $file = $this->docsPath.'/'.ltrim(config('docs.config_file', '.docs.yaml'), '/');
        file_put_contents($file, $content);
    }

    protected function writeDoc(string $rel, string $content): void
    {
        $path = $this->docsPath.'/'.$rel;
        $dir = dirname($path);
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        file_put_contents($path, $content);
    }

    protected function removeDir(string $dir): void
    {
        if (!is_dir($dir)) {
            return;
        }
        $items = scandir($dir);
        foreach ($items as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }
            $full = $dir.'/'.$item;
            is_dir($full) ? $this->removeDir($full) : unlink($full);
        }
        rmdir($dir);
    }
}
