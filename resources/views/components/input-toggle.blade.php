{{-- @props(['type'])
<button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn btn-' . (isset($type) ? $type : 'primary')]) }}>
    {{ $slot }}
</button> --}}

@props(['value' => ''])

<div x-data="{toggle: true}">
    <span x-show="!toggle" class="cursor-pointer" x-on:click="toggle=true;">{{ $value }}</span>
    <div x-show="toggle">{!! $slot !!}</div>
</div>
