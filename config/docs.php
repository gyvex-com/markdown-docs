<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Docs Path
    |--------------------------------------------------------------------------
    |
    | The absolute path to the folder containing your markdown (.md) files.
    | Subdirectories are mapped to nested routes.
    |
    */
    'path' => base_path('docs'),

    /*
    |--------------------------------------------------------------------------
    | Route Prefix
    |--------------------------------------------------------------------------
    |
    | The URL prefix under which docs will be served. Set to an empty string
    | to disable route registration.
    |
    */
    'route_prefix' => 'docs',

    /*
    |--------------------------------------------------------------------------
    | Middleware
    |--------------------------------------------------------------------------
    |
    | Middleware applied to the docs routes.
    |
    */
    'middleware' => ['web'],

    /*
    |--------------------------------------------------------------------------
    | Index File
    |--------------------------------------------------------------------------
    |
    | Filename (without extension) used for the root page and for any directory
    | without an explicit path segment.
    |
    */
    'index' => 'index',

    /*
    |--------------------------------------------------------------------------
    | Config File
    |--------------------------------------------------------------------------
    |
    | The name of the optional per-docs-folder YAML config file (located at the
    | docs root). It can define a `stylesheet` and a `menu` to override the
    | auto-generated sidebar, and may hold additional page-level settings.
    |
    */
    'config_file' => '.docs.yaml',

    /*
    |--------------------------------------------------------------------------
    | Cache
    |--------------------------------------------------------------------------
    |
    | When enabled, rendered HTML is cached keyed by file path and mtime, so the
    | markdown is only parsed again after the source file changes.
    |
    */
    'cache' => true,

];
