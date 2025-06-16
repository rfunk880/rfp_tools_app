<?php

namespace Support\Traits;

use Illuminate\Support\Arr;

trait LivewireAlert
{
    public function confirm(string $title, $options = [])
    {
        $options = array_merge(
            config('livewire-alert.confirm', []),
            $options,
            [
                'icon' => 'success',
                'showConfirmButton' => true,
                'showCancelButton' => true,
                'cancelButtonText' => 'No',
                'confirmButtonColor' => '#3085d6',
                'cancelButtonColor' => '#d33'
            ]
        );

        $type = Arr::get($options, 'icon');

        $this->alert($type, $title, $options);
    }

    public function alert(string $type = 'success', string $message = '', array $options = [])
    {
        $this->dispatchOrFlashAlert([
            'type' => $type,
            'message' => $message,
            'options' => $options
        ]);
    }

    public function flash(string $type = 'success', string $message = '', array $options = [], $redirect = '/')
    {
        $this->dispatchOrFlashAlert([
            'type' => $type,
            'message' => $message,
            'options' => $options,
            'flash' => true
        ]);

        return redirect()->to($redirect);
    }

    protected function dispatchOrFlashAlert(array $configuration)
    {
        $type = Arr::get($configuration, 'type');

        $message = Arr::get($configuration, 'message');

        $events = collect(Arr::only(
            Arr::get($configuration, 'options'),
            $this->livewireAlertEvents()
        ))
            ->map(function ($event) {
                return $this->getEventProperties($event);
            })
            ->toArray();

        $data = Arr::get($configuration, 'options.data');

        $options = Arr::only(
            Arr::get($configuration, 'options'),
            $this->configurationKeys()
        );

        $isFlash = Arr::has($configuration, 'flash') && Arr::get($configuration, 'flash') === true;

        $options = array_merge(
            config('livewire-alert.alert') ?? [],
            config('livewire-alert.' . $type) ?? [],
            $options
        );

        if (!in_array($type, $this->livewireAlertIcons())) {
            throw new \Exception(
                "Invalid '{$type}' alert icon."
            );
        }

        $payload = [
            'type' => $type,
            'message' => $message,
            'events' => $events,
            'options' => $options,
            'data' => $data,
        ];

        if (!$isFlash) {
            $this->dispatchBrowserEvent('alert', $payload);

            return;
        }

        session()->flash('livewire-alert', $payload);
    }

    protected function getEventProperties($event)
    {
        $expectedKeys = ['id', 'component', 'listener'];

        if (is_array($event)) {
            $event = Arr::only($event, $expectedKeys);

            if (!Arr::exists($event, 'component')) {
                throw new Exception('Missing component key on event properties');
            }

            if (!Arr::exists($event, 'listener')) {
                throw new Exception('Missing listener key on event properties');
            }

            Arr::set($event, 'id', null);

            return $event;
        }

        return [
            'id' => $this->id,
            'component' =>  'self',
            'listener' => $event
        ];
    }

    protected function livewireAlertIcons(): array
    {
        return [
            '',
            'success',
            'info',
            'warning',
            'error',
            'question'
        ];
    }

    protected function livewireAlertEvents(): array
    {
        return [
            'onConfirmed',
            'onDismissed',
            'onDenied',
            'onProgressFinished'
        ];
    }

    protected function configurationKeys(): array
    {
        return [
            'title',
            'titleText',
            'html',
            'text',
            'icon',
            'iconColor',
            'iconHtml',
            'showClass',
            'hideClass',
            'footer',
            'backdrop',
            'toast',
            'target',
            'input',
            'width',
            'padding',
            'color',
            'background',
            'position',
            'grow',
            'customClass',
            'timer',
            'timerProgressBar',
            'heightAuto',
            'allowOutsideClick',
            'allowEscapeKey',
            'allowEnterKey',
            'stopKeydownPropagation',
            'keydownListenerCapture',
            'showConfirmButton',
            'showDenyButton',
            'showCancelButton',
            'confirmButtonText',
            'denyButtonText',
            'cancelButtonText',
            'confirmButtonText',
            'confirmButtonColor',
            'denyButtonColor',
            'cancelButtonColor',
            'confirmButtonAriaLabel',
            'denyButtonAriaLabel',
            'cancelButtonAriaLabel',
            'buttonsStyling',
            'reverseButtons',
            'focusConfirm',
            'returnFocus',
            'focusDeny',
            'focusCancel',
            'showCloseButton',
            'closeButtonHtml',
            'closeButtonAriaLabel',
            'loaderHtml',
            'showLoaderOnConfirm',
            'showLoaderOnDeny',
            'scrollbarPadding',
            'preConfirm',
            'preDeny',
            'returnInputValueOnDeny',
            'imageUrl',
            'imageWidth',
            'imageHeight',
            'imageAlt',
            'inputLabel',
            'inputPlaceholder',
            'inputValue',
            'inputOptions',
            'inputAutoTrim',
            'inputAttributes',
            'inputValidator',
            'validationMessage',
            'progressSteps',
            'currentProgressStep',
            'progressStepsDistance',
            'willOpen',
            'didOpen',
            'didRender',
            'willClose',
            'didClose',
            'didDestroy'
        ];
    }
}
