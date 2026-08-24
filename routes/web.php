<?php

use GyvexCom\MarkdownDocs\Http\Controllers\DocsController;
use GyvexCom\MarkdownDocs\Http\Controllers\DocsStylesheetController;
use Illuminate\Support\Facades\Route;

$prefix = trim(config('docs.route_prefix', 'docs'), '/');

if ($prefix !== '') {
    Route::middleware(config('docs.middleware', ['web']))
        ->group(function () use ($prefix) {
            Route::get($prefix.'/stylesheet', [DocsStylesheetController::class, 'stylesheet'])
                ->name('markdown-docs.stylesheet');

            Route::get($prefix.'/{path?}', [DocsController::class, 'show'])
                ->where('path', '.*')
                ->name('markdown-docs.show');
        });
}
