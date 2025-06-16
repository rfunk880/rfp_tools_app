@props([
    'error' => null,
])

<div x-data="{ value: @entangle($attributes->wire('model')) }"
     x-on:change="value = $event.target.value;"
     x-init=" new tempusDominus.TempusDominus($refs.input, {
          allowInputToggle: false,
          container: undefined,
          dateRange: false,
          debug: false,
          defaultDate: value ? value : undefined,
          localization: {
              format: 'MM-dd-yyyy HH:mm'
          },
          display: {
     
              sideBySide: true,
              calendarWeeks: false,
              viewMode: 'calendar',
              toolbarPlacement: 'bottom',
              keepOpen: false,
              buttons: {
                  today: true,
                  clear: false,
                  close: true
              },
              components: {
                  calendar: true,
                  date: true,
                  month: true,
                  year: true,
                  decades: true,
                  clock: true,
                  hours: true,
                  minutes: true,
                  seconds: false,
                  useTwentyfourHour: undefined
              },
              inline: false,
              theme: 'auto'
          },
          keepInvalid: false,
          useCurrent: false
      });">
    <input {{ $attributes->whereDoesntStartWith('wire:model') }}
           x-ref="input"
           x-bind:value="value"
           type="text"
           onchange="@this.set('{{ $attributes->wire('model')->value() }}', event.target.value)"
           class="form-control @if ($error) focus:ring-danger-500 focus:border-danger-500 border-danger-500 text-danger-500 pr-10 @else focus:ring-primary-500 focus:border-primary-500 @endif rounded-md"
           {{-- onchange="@this.set('{{ $attributes->wire('model')->value() }}', event.target.value)" --}} />
</div>
