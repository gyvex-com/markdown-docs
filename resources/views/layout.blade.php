<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Docs' }} &middot; {{ config('docs.route_prefix', 'docs') }}</title>
    <style>
        :root {
            --md-brand: #2563eb;
            --md-text: #1f2937;
            --md-muted: #6b7280;
            --md-border: #e5e7eb;
            --md-bg: #ffffff;
            --md-sidebar-bg: #f9fafb;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: ui-sans-serif, system-ui, -apple-system, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            color: var(--md-text);
            background: var(--md-bg);
            line-height: 1.6;
        }
        .md-layout { display: flex; min-height: 100vh; }
        .md-sidebar {
            width: 260px;
            flex-shrink: 0;
            background: var(--md-sidebar-bg);
            border-right: 1px solid var(--md-border);
            padding: 1.5rem 1rem;
            overflow-y: auto;
        }
        .md-sidebar .md-brand {
            font-weight: 700;
            font-size: 1.1rem;
            margin-bottom: 1rem;
            color: var(--md-brand);
        }
        .md-sidebar ul { list-style: none; margin: 0; padding: 0; }
        .md-sidebar li { margin: 0; }
        .md-sidebar a {
            display: block;
            padding: 0.35rem 0.6rem;
            border-radius: 0.375rem;
            color: var(--md-text);
            text-decoration: none;
            font-size: 0.925rem;
        }
        .md-sidebar a:hover { background: #eef2ff; }
        .md-sidebar a[aria-current="page"] {
            background: var(--md-brand);
            color: #fff;
            font-weight: 600;
        }
        .md-sidebar .md-children { padding-left: 0.85rem; }
        .md-content {
            flex: 1;
            padding: 2.5rem 3rem;
            max-width: 880px;
        }
        .md-content h1 { margin-top: 0; }
        .md-content pre {
            background: #0f172a;
            color: #e2e8f0;
            padding: 1rem;
            border-radius: 0.5rem;
            overflow-x: auto;
        }
        .md-content code { background: #f3f4f6; padding: 0.15rem 0.35rem; border-radius: 0.25rem; }
        .md-content pre code { background: transparent; padding: 0; }
        .md-content table { border-collapse: collapse; width: 100%; }
        .md-content th, .md-content td { border: 1px solid var(--md-border); padding: 0.5rem 0.75rem; }
        .md-content blockquote { border-left: 4px solid var(--md-border); margin: 1rem 0; padding: 0 1rem; color: var(--md-muted); }
        @media (max-width: 720px) {
            .md-layout { flex-direction: column; }
            .md-sidebar { width: 100%; border-right: none; border-bottom: 1px solid var(--md-border); }
            .md-content { padding: 1.5rem; }
        }
    </style>
    @if (!empty($stylesheetUrl))
        <link rel="stylesheet" href="{{ $stylesheetUrl }}">
    @endif
</head>
<body>
    <div class="md-layout">
        @include('markdown-docs::partials.sidebar', ['nodes' => $tree ?? [], 'activePath' => $activePath ?? ''])
        <main class="md-content">
            @yield('content')
        </main>
    </div>
</body>
</html>
