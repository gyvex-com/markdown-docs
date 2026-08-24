@if (!empty($nodes))
    <nav class="md-sidebar" aria-label="Documentation">
        <div class="md-brand">{{ ucfirst(config('docs.route_prefix', 'docs')) }}</div>
        <ul>
            @foreach ($nodes as $node)
                @include('markdown-docs::partials.sidebar-item', [
                    'node' => $node,
                    'activePath' => $activePath,
                ])
            @endforeach
        </ul>
    </nav>
@endif
