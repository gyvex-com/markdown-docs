@php
    $isActive = ($activePath === $node['url']);
    // A directory is also considered active when it contains the active page.
    $childActive = false;
    if (!empty($node['children'])) {
        foreach ($node['children'] as $child) {
            if (($child['url'] ?? '') === $activePath) {
                $childActive = true;
                break;
            }
        }
    }
@endphp

<li>
    <a href="{{ $node['url'] }}"
       @if ($isActive) aria-current="page" @endif
       @if ($isActive || $childActive) class="md-active" @endif>
        {{ $node['title'] }}
    </a>

    @if (!empty($node['children']))
        <ul class="md-children">
            @foreach ($node['children'] as $child)
                @include('markdown-docs::partials.sidebar-item', [
                    'node' => $child,
                    'activePath' => $activePath,
                ])
            @endforeach
        </ul>
    @endif
</li>
