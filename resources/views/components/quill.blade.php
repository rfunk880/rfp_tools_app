@props([
    'error' => null,
    'height' => '300px',
])

<div wire:ignore
     x-data="{ value: @entangle($attributes->wire('model')) }"
     x-on:change="value = $event.target.value"
     x-init=" new Quill($refs.input, {
          theme: 'snow',
          debug: 'error'
      });">
    <textarea wire:ignore {{ $attributes->whereDoesntStartWith('wire:model') }}
              x-ref="input"
              x-bind:value="value"
              style="height: {{ $height }}"
              class="form-control @if ($error) focus:ring-danger-500 focus:border-danger-500 border-danger-500 text-danger-500 pr-10 @else focus:ring-primary-500 focus:border-primary-500 @endif rounded-md"></textarea>
</div>
