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
    | Branding
    |--------------------------------------------------------------------------
    |
    | Controls the brand shown in the sidebar header and the mobile topbar.
    |
    |  - `logo`   A path or URL to a logo image. It can be an absolute URL, a
    |             path relative to the docs root (served by the package's logo
    |             route), or an absolute web path starting with `/`. When set,
    |             it takes precedence over `text`.
    |  - `text`   The brand text shown when no `logo` is configured. When null,
    |             the capitalized route prefix is used as a fallback.
    |
    | Both values can also be set per docs folder via the `logo` and `text`
    | keys in the `.docs.yaml` config file, which take precedence over these.
    |
    */
    'branding' => [
        'logo' => null,
        'text' => null,
    ],

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
    | Dark Mode
    |--------------------------------------------------------------------------
    |
    | When enabled, the default documentation UI is rendered in dark mode.
    | This only affects the built-in styles; a custom stylesheet (from the
    | `.docs.yaml` config file) always takes precedence.
    |
    */
    'dark_mode' => false,

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
