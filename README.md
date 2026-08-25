# Markdown Docs for Laravel

Render a configurable folder of Markdown files as a browsable `/docs` site in Laravel - with nested path routing, an auto-generated sidebar, and front-matter driven navigation. No database, no build step: point it at a directory and it just works.

[![Latest Stable Version](https://img.shields.io/packagist/v/gyvex-com/markdown-docs.svg)](https://packagist.org/packages/gyvex-com/markdown-docs)
[![License](https://img.shields.io/packagist/l/gyvex-com/markdown-docs.svg)](https://packagist.org/packages/gyvex-com/markdown-docs)

## Features

- **Zero-config routing** - Every `.md` file under your docs folder becomes a route automatically. Subdirectories map to nested paths (`/docs/guides/installation`).
- **Auto-generated sidebar** - A navigation tree is built from your folder structure and front-matter `order`, so the menu stays in sync with your files.
- **Front matter** - Set `title` and `order` per page with a leading YAML block.
- **Customizable navigation** - Optionally replace the auto-generated sidebar with a hand-written menu via a `.docs.yaml` file.
- **Custom styling** - Ship your own stylesheet for the docs UI without overriding views.
- **Safe rendering** - GitHub-flavored Markdown via [`league/commonmark`](https://commonmark.thephpleague.com/), with unsafe HTML escaped and unsafe links disabled.
- **Caching** - Rendered HTML is cached keyed by file path + modification time, so pages only re-parse when the source changes.
- **Path-traversal protection** - Resolved files are guaranteed to live inside the configured docs root.

## Requirements

- PHP `^8.1`
- Laravel `^11 | ^12 | ^13` (`illuminate/*` components)

## Installation

Install the package via Composer:

```bash
composer require gyvex-com/markdown-docs
```

The service provider is auto-discovered by Laravel. If you have disabled package discovery, register `GyvexCom\MarkdownDocs\MarkdownDocsServiceProvider` manually.

## Quick start

1. Create a `docs/` folder at the root of your Laravel project:

   ```bash
   mkdir docs
   ```

2. Add a Markdown file named `index.md` (this becomes the landing page):

   ```markdown
   # Welcome

   This documentation site is rendered from Markdown files.
   ```

3. Visit `/docs` in your browser.

Any other Markdown file you drop in (e.g. `docs/guides/installation.md`) is immediately available at `/docs/guides/installation`.

## How routing works

The package registers a single catch-all route under your configured prefix:

```
GET /{prefix}/{path?}
```

The `{path}` segment is the relative path of a Markdown file (without the `.md` extension). Directory `index.md` files serve as the landing page of their folder, so a directory link stays clean (`/docs/guides` instead of `/docs/guides/index`).

| File on disk                   | URL                          |
| ------------------------------ | ---------------------------- |
| `docs/index.md`                | `/docs`                      |
| `docs/getting-started.md`      | `/docs/getting-started`      |
| `docs/guides/index.md`         | `/docs/guides`               |
| `docs/guides/installation.md`  | `/docs/guides/installation`  |

## Configuration

Publish the config file to customize behavior:

```bash
php artisan vendor:publish --tag=markdown-docs-config
```

The published file lives at `config/docs.php`:

```php
return [
    // Absolute path to the folder containing your .md files.
    'path' => base_path('docs'),

    // URL prefix under which docs are served. Empty string disables routing.
    'route_prefix' => 'docs',

    // Middleware applied to the docs routes.
    'middleware' => ['web'],

    // Filename (no extension) used for the root and per-directory landing pages.
    'index' => 'index',

    // Optional per-docs-folder YAML config (stylesheet + menu overrides).
    'config_file' => '.docs.yaml',

    // Render the built-in UI in dark mode. A custom stylesheet still wins.
    'dark_mode' => false,

    // Cache rendered HTML, keyed by file path and mtime.
    'cache' => true,
];
```

### Protecting docs

Because docs are public by default, restrict access by adjusting the `middleware` config - e.g. wrap the routes in `auth` or a custom gate:

```php
'middleware' => ['web', 'auth'],
```

## Front matter

Each Markdown file can start with a YAML front-matter block delimited by `---` lines:

```markdown
---
title: Installation
order: 2
---

# Installation

Content goes here...
```

| Key     | Type   | Description                                                                 |
| ------- | ------ | --------------------------------------------------------------------------- |
| `title` | string | Page title used in the sidebar and `<title>`. Falls back to the filename.   |
| `order` | int    | Sort order within its level. Directory index files default to `-1` (first). |

## Customizing the sidebar (`.docs.yaml`)

By default the sidebar is built from your folder structure. To define it explicitly, add a `.docs.yaml` file at your docs root:

```yaml
stylesheet: styles/docs.css

menu:
  - title: Introduction
    url: /docs
  - title: Getting Started
    url: /docs/getting-started
    children:
      - title: Installation
        url: /docs/getting-started/installation
      - title: Configuration
        url: /docs/getting-started/configuration
  - title: Advanced
    url: /docs/advanced
```

- `stylesheet` - a path (served by the package's stylesheet route) that overrides the default UI styling.
- `menu` - a nested list of `title` / `url` / `children` items. When present, it replaces the auto-generated tree.

## Customizing the views

You can override the Blade views instead of (or in addition to) the stylesheet:

```bash
php artisan vendor:publish --tag=markdown-docs-views
```

The published views appear in `resources/views/vendor/markdown-docs/` and use the `markdown-docs::` namespace:

- `layout.blade.php` - the HTML shell, sidebar, and embedded CSS.
- `docs.blade.php` - the content section wrapper.
- `partials/sidebar.blade.php` - the navigation tree renderer.
- `partials/sidebar-item.blade.php` - a single (recursive) navigation node.

## Programmatic usage

The `MarkdownDocs` facade (or the `markdown-docs` container binding) exposes the underlying manager:

```php
use MarkdownDocs;

// Resolve a doc by path (without .md), or null if missing.
$doc = MarkdownDocs::resolve('guides/installation');

// Rendered HTML for a doc.
$html = MarkdownDocs::render($doc);

// The navigation tree (array of nodes with title/url/order/children).
$tree = MarkdownDocs::tree();
```

The bound manager is also available as `GyvexCom\MarkdownDocs\MarkdownDocsManager`.

## Testing

The package uses [Orchestra Testbench](https://packages.tools/orchestra-testbench/) and PHPUnit:

```bash
composer test
```

## Security

- HTML input in Markdown is escaped and unsafe links are disabled by default.
- The filesystem repository validates that every resolved file lives inside the configured docs root, preventing path-traversal.

## License

Markdown Docs for Laravel is open-sourced software licensed under the [MIT license](LICENSE).
