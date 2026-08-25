<!DOCTYPE html>
<html lang="en" @if ($darkMode ?? false) class="md-dark" @endif>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Docs' }} &middot; {{ config('docs.route_prefix', 'docs') }}</title>
    <style>
        :root {
            --md-brand: #4f46e5;
            --md-brand-soft: #eef2ff;
            --md-text: #1f2430;
            --md-muted: #6b7280;
            --md-border: #e6e8ee;
            --md-bg: #ffffff;
            --md-sidebar-bg: #fbfbfd;
            --md-topbar-h: 56px;
            --md-radius: 0.5rem;
        }
        html.md-dark {
            --md-brand: #818cf8;
            --md-brand-soft: #1f2733;
            --md-text: #e5e7eb;
            --md-muted: #9aa3b2;
            --md-border: #2a2f3a;
            --md-bg: #0f1117;
            --md-sidebar-bg: #14171f;
        }
        html.md-dark .md-content code {
            color: #e2e8f0;
            background: #1f2733;
            border: 1px solid #2a3340;
        }
        html.md-dark .md-content pre { background: #06080d; }
        html.md-dark .md-menu-toggle,
        html.md-dark .md-sidebar .md-close { color: var(--md-muted); }
        * { box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body {
            margin: 0;
            font-family: ui-sans-serif, system-ui, -apple-system, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            color: var(--md-text);
            background: var(--md-bg);
            line-height: 1.65;
            -webkit-font-smoothing: antialiased;
        }

        /* Topbar (mobile only) */
        .md-topbar {
            display: none;
            position: sticky;
            top: 0;
            z-index: 30;
            align-items: center;
            gap: 0.75rem;
            height: var(--md-topbar-h);
            padding: 0 1rem;
            background: var(--md-bg);
            border-bottom: 1px solid var(--md-border);
        }
        .md-topbar .md-brand { font-weight: 700; font-size: 1.05rem; color: var(--md-brand); }
        .md-menu-toggle {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 38px;
            height: 38px;
            padding: 0;
            border: 1px solid var(--md-border);
            border-radius: var(--md-radius);
            background: var(--md-bg);
            color: var(--md-text);
            cursor: pointer;
        }
        .md-menu-toggle:hover { background: var(--md-brand-soft); }
        .md-menu-toggle svg { width: 20px; height: 20px; }

        /* Layout */
        .md-layout { display: flex; min-height: 100vh; }

        /* Sidebar */
        .md-sidebar {
            width: 280px;
            flex-shrink: 0;
            align-self: flex-start;
            position: sticky;
            top: 0;
            height: 100vh;
            overflow-y: auto;
            background: var(--md-sidebar-bg);
            border-right: 1px solid var(--md-border);
            padding: 1.75rem 1rem;
        }
        .md-sidebar .md-sidebar-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.25rem;
        }
        .md-sidebar .md-brand {
            font-weight: 700;
            font-size: 1.15rem;
            letter-spacing: -0.01em;
            color: var(--md-brand);
            text-decoration: none;
        }
        .md-sidebar .md-close {
            display: none;
            border: none;
            background: transparent;
            font-size: 1.5rem;
            line-height: 1;
            color: var(--md-muted);
            cursor: pointer;
        }
        .md-sidebar ul { list-style: none; margin: 0; padding: 0; }
        .md-sidebar li { margin: 0; }
        .md-sidebar a {
            display: block;
            padding: 0.4rem 0.7rem;
            border-radius: 0.4rem;
            color: var(--md-text);
            text-decoration: none;
            font-size: 0.925rem;
            transition: background-color 0.12s ease, color 0.12s ease;
        }
        .md-sidebar a:hover { background: var(--md-brand-soft); }
        .md-sidebar a[aria-current="page"] {
            background: var(--md-brand);
            color: #fff;
            font-weight: 600;
        }
        .md-sidebar a.md-active:not([aria-current="page"]) {
            color: var(--md-brand);
            font-weight: 600;
        }
        .md-sidebar .md-children {
            padding-left: 0.85rem;
            margin-left: 0.45rem;
            border-left: 1px solid var(--md-border);
        }

        /* Content */
        .md-content {
            flex: 1;
            min-width: 0;
            padding: 3rem 3.5rem;
            max-width: 920px;
            margin: 0 auto;
        }
        .md-content h1 { margin-top: 0; font-size: 2rem; letter-spacing: -0.02em; }
        .md-content h2 { margin-top: 2rem; font-size: 1.45rem; letter-spacing: -0.01em; }
        .md-content h3 { margin-top: 1.5rem; font-size: 1.2rem; }
        .md-content p { margin: 1rem 0; }
        .md-content a { color: var(--md-brand); text-decoration: none; }
        .md-content a:hover { text-decoration: underline; }
        .md-content img { max-width: 100%; height: auto; border-radius: var(--md-radius); }
        .md-content pre {
            background: #0f172a;
            color: #e2e8f0;
            padding: 1.1rem 1.25rem;
            border-radius: var(--md-radius);
            overflow-x: auto;
            font-size: 0.875rem;
            line-height: 1.6;
        }
        .md-content code {
            font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, monospace;
            background: var(--md-brand-soft);
            color: #4338ca;
            padding: 0.15rem 0.35rem;
            border-radius: 0.3rem;
            font-size: 0.875em;
        }
        .md-content pre code { background: transparent; color: inherit; padding: 0; }
        .md-content table { border-collapse: collapse; width: 100%; margin: 1.25rem 0; font-size: 0.95rem; }
        .md-content th, .md-content td { border: 1px solid var(--md-border); padding: 0.6rem 0.85rem; text-align: left; }
        .md-content th { background: var(--md-sidebar-bg); font-weight: 600; }
        .md-content tbody tr:nth-child(even) { background: var(--md-sidebar-bg); }
        .md-content blockquote {
            border-left: 4px solid var(--md-brand);
            margin: 1.25rem 0;
            padding: 0.25rem 1.1rem;
            color: var(--md-muted);
            background: var(--md-brand-soft);
            border-radius: 0 var(--md-radius) var(--md-radius) 0;
        }
        .md-content hr { border: none; border-top: 1px solid var(--md-border); margin: 2rem 0; }

        /* Mobile backdrop */
        .md-backdrop {
            display: none;
            position: fixed;
            inset: 0;
            z-index: 20;
            background: rgba(15, 23, 42, 0.45);
            opacity: 0;
            transition: opacity 0.2s ease;
        }

        @media (max-width: 860px) {
            .md-topbar { display: flex; }
            .md-layout { display: block; min-height: auto; }
            .md-sidebar {
                position: fixed;
                top: 0;
                left: 0;
                z-index: 25;
                height: 100vh;
                width: 82%;
                max-width: 320px;
                transform: translateX(-100%);
                transition: transform 0.25s ease;
                box-shadow: 0 10px 30px rgba(15, 23, 42, 0.18);
            }
            .md-sidebar.md-sidebar--open { transform: translateX(0); }
            .md-sidebar .md-close { display: block; }
            .md-content { padding: 1.5rem 1.25rem 3rem; max-width: 100%; }
            .md-backdrop.md-backdrop--show { display: block; opacity: 1; }
        }

        @media (prefers-reduced-motion: reduce) {
            html { scroll-behavior: auto; }
            .md-sidebar, .md-backdrop { transition: none; }
        }
    </style>
    @if (!empty($stylesheetUrl))
        <link rel="stylesheet" href="{{ $stylesheetUrl }}">
    @endif
</head>
<body>
    <header class="md-topbar">
        <button type="button" class="md-menu-toggle" aria-label="Toggle navigation" aria-expanded="false" aria-controls="md-sidebar">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true">
                <line x1="3" y1="6" x2="21" y2="6"></line>
                <line x1="3" y1="12" x2="21" y2="12"></line>
                <line x1="3" y1="18" x2="21" y2="18"></line>
            </svg>
        </button>
        <span class="md-brand">{{ ucfirst(config('docs.route_prefix', 'docs')) }}</span>
    </header>

    <div class="md-layout">
        @include('markdown-docs::partials.sidebar', ['nodes' => $tree ?? [], 'activePath' => $activePath ?? ''])
        <main class="md-content">
            @yield('content')
        </main>
    </div>

    <div class="md-backdrop" aria-hidden="true"></div>

    <script>
        (function () {
            var body = document.body;
            var toggle = body.querySelector('.md-menu-toggle');
            var sidebar = body.querySelector('.md-sidebar');
            var close = body.querySelector('.md-sidebar .md-close');
            var backdrop = body.querySelector('.md-backdrop');
            if (!toggle || !sidebar || !backdrop) return;

            function open() {
                sidebar.classList.add('md-sidebar--open');
                backdrop.classList.add('md-backdrop--show');
                toggle.setAttribute('aria-expanded', 'true');
                document.documentElement.style.overflow = 'hidden';
            }
            function shut() {
                sidebar.classList.remove('md-sidebar--open');
                backdrop.classList.remove('md-backdrop--show');
                toggle.setAttribute('aria-expanded', 'false');
                document.documentElement.style.overflow = '';
            }

            toggle.addEventListener('click', function () {
                sidebar.classList.contains('md-sidebar--open') ? shut() : open();
            });
            if (close) close.addEventListener('click', shut);
            backdrop.addEventListener('click', shut);
            sidebar.addEventListener('click', function (e) {
                if (e.target.closest('a')) shut();
            });
        })();
    </script>
</body>
</html>
