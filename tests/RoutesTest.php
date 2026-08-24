<?php

namespace GyvexCom\MarkdownDocs\Tests;

use GyvexCom\MarkdownDocs\MarkdownDocsServiceProvider;

class RoutesTest extends PackageTestCase
{
    protected function getPackageProviders($app): array
    {
        return [MarkdownDocsServiceProvider::class];
    }

    public function test_docs_index_route_renders(): void
    {
        $this->writeDoc('index.md', "---\ntitle: Home\n---\n# Home\nWelcome");
        $this->writeDoc('getting-started/install.md', "---\ntitle: Install\n---\n# Install\nSteps here");

        $this->get('/docs')->assertStatus(200)
            ->assertSee('Welcome')
            ->assertSee('Getting Started');

        $this->get('/docs/getting-started/install')->assertStatus(200)
            ->assertSee('Steps here')
            ->assertSee('Install');
    }

    public function test_nested_path_with_slashes_matches_route(): void
    {
        $this->writeDoc('a/b/c.md', "---\ntitle: Deep\n---\n# Deep\nNested content");

        $this->get('/docs/a/b/c')->assertStatus(200)->assertSee('Nested content');
    }

    public function test_traversal_returns_404(): void
    {
        $this->get('/docs/../../../../../../etc/passwd')->assertStatus(404);
    }

    public function test_missing_page_returns_404(): void
    {
        $this->get('/docs/nope/not-here')->assertStatus(404);
    }

    public function test_route_is_registered_with_constraint(): void
    {
        $routes = \Illuminate\Support\Facades\Route::getRoutes();

        $matched = false;
        foreach ($routes as $route) {
            if (in_array('markdown-docs.show', $route->getName() ? [$route->getName()] : [], true)) {
                $matched = true;
                $this->assertStringContainsString('.*', implode(',', $route->wheres));
            }
        }

        $this->assertTrue($matched, 'docs route is registered');
    }

    public function test_stylesheet_route_serves_configured_css(): void
    {
        $this->writeDoc('index.md', "---\ntitle: Home\n---\n# Home");
        $this->writeDoc('assets/style.css', '.md-brand { color: red; }');
        $this->writeConfig("stylesheet: assets/style.css\n");

        $this->get('/docs/stylesheet')
            ->assertStatus(200)
            ->assertHeader('Content-Type', 'text/css; charset=utf-8')
            ->assertSee('.md-brand { color: red; }');
    }

    public function test_stylesheet_route_404_when_unconfigured(): void
    {
        $this->writeDoc('index.md', "---\ntitle: Home\n---\n# Home");

        $this->get('/docs/stylesheet')->assertStatus(404);
    }

    public function test_stylesheet_traversal_blocked(): void
    {
        $this->writeDoc('index.md', "---\ntitle: Home\n---\n# Home");
        $this->writeConfig("stylesheet: ../secret.css\n");

        $this->get('/docs/stylesheet')->assertStatus(404);
    }

    public function test_menu_replaces_auto_tree(): void
    {
        $this->writeDoc('index.md', "---\ntitle: Home\n---\n# Home");
        $this->writeDoc('getting-started/install.md', "---\ntitle: Install\n---\n# Install");
        $this->writeConfig(implode("\n", [
            'menu:',
            '  - title: Custom Home',
            '    url: /docs',
            '  - title: Custom Install',
            '    url: /docs/getting-started/install',
            '',
        ]));

        $this->get('/docs')
            ->assertStatus(200)
            ->assertSee('Custom Home')
            ->assertSee('Custom Install')
            ->assertDontSee('Getting Started');
    }

    public function test_without_config_file_uses_auto_tree(): void
    {
        $this->writeDoc('index.md', "---\ntitle: Home\n---\n# Home");
        $this->writeDoc('getting-started/install.md', "---\ntitle: Install\n---\n# Install");

        $this->get('/docs')
            ->assertStatus(200)
            ->assertSee('Getting Started');
    }
}
