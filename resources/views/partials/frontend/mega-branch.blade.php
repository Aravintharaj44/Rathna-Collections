@php $depth = $depth ?? 1; @endphp

<ul class="rc-mega-list {{ $depth > 1 ? 'rc-mega-sublist' : '' }}">
    @foreach ($items as $node)
        <li>
            <a href="{{ route('shop.index', ['category' => $node->slug]) }}">{{ $node->name }}</a>

            @if ($depth < 3 && $node->relationLoaded('children') && $node->children->isNotEmpty())
                @include('partials.frontend.mega-branch', [
                    'items' => $node->children,
                    'depth' => $depth + 1,
                ])
            @endif
        </li>
    @endforeach
</ul>