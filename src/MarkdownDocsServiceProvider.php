<?php

namespace GyvexCom\MarkdownDocs;

use GyvexCom\MarkdownDocs\Contracts\MarkdownDocsRepository;
use GyvexCom\MarkdownDocs\MarkdownDocsManager;
use GyvexCom\MarkdownDocs\Repositories\FilesystemRepository;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class MarkdownDocsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/docs.php', 'docs');

        $this->app->bind(MarkdownDocsRepository::class, FilesystemRepository::class);

        $this->app->singleton('markdown-docs', function ($app) {
            return new MarkdownDocsManager($app->make(MarkdownDocsRepository::class));
        });

        $this->app->singleton(MarkdownDocsManager::class, function ($app) {
            return new MarkdownDocsManager($app->make(MarkdownDocsRepository::class));
        });
    }

    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'markdown-docs');

        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/docs.php' => config_path('docs.php'),
            ], 'markdown-docs-config');

            $this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/markdown-docs'),
            ], 'markdown-docs-views');
        }
    }
}
