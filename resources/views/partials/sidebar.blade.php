@if (!empty($nodes))
    <nav id="md-sidebar" class="md-sidebar" aria-label="Documentation">
        <div class="md-sidebar-header">
            <a href="{{ url(config('docs.route_prefix', 'docs')) }}" class="md-brand">{{ ucfirst(config('docs.route_prefix', 'docs')) }}</a>
            <button type="button" class="md-close" aria-label="Close navigation">&times;</button>
        </div>
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
