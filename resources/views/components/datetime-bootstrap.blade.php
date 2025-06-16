@props([
    'error' => null,
])
<div x-data="{ value: @entangle($attributes->wire('model')) }"
     >
    <input {{ $attributes->whereDoesntStartWith('wire:model') }}
           x-ref="input"
           x-bind:value="value"
           type="text"
           {{-- {{ $attributes->wire('model')->directive().'="'.$attributes->wire('model')->value().'"'}} --}}
           class="form-control datepicker"
          
           data-provide="datetimepicker"
           data-date-autoclose="true"
           data-date-format="mm-dd-yyyy HH:ii"
           data-date-today-highlight="true"
           onchange="@this.set('{{ $attributes->wire('model')->value()}}', event.target.value)" />
</div>
