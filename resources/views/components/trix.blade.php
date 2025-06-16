@props([
    'error' => null,
    'height' => '260px',
])
<div x-data="{ value: @entangle($attributes->wire('model')) }">
    <div class="mb-4"
         {{ $attributes->wire('model') }}
         wire:ignore>
        <input type="hidden" {{ $attributes->whereDoesntStartWith('wire:model') }} type="hidden" >
        <trix-editor style="overflow-y:auto;height: {{ $height }};" class="trix-content" input="{{ $attributes->get('id') }}"></trix-editor>
    </div>
</div>
