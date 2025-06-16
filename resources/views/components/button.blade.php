{{-- @props(['type'])
<button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn btn-' . (isset($type) ? $type : 'primary')]) }}>
    {{ $slot }}
</button> --}}

@props(['loading' => false, 'target' => false, 'color' => 'primary'])

<button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn btn-' . (isset($color) ? $color : 'primary')]) }}
        wire:loading.class="disabled">
    @php
        if (!$target) {
            $target = $attributes->wire('click')->value();
        }
    @endphp
    @if ($loading && $target)
        <div wire:loading.remove
             wire:target="{{ $target }}">{{ $slot }}</div>
        <div wire:loading
             wire:target="{{ $target }}">{{ $loading }}</div>
    @else
        {{ $slot }}
    @endif
</button>
