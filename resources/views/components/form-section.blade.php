@props(['submit'])

<div {{ $attributes->merge(['class' => 'card']) }}>
    <form wire:submit="{{ $submit }}">
    <div class="card-header">
        <h2 name="title">{{ $title }}</h2>
        <p name="description">{{ $description }}</p>
    </div>

    <div class="card-body">
            <div class="">
                <div class="grid grid-cols-6 gap-6">
                    {{ $form }}
                </div>
            </div>

        </div>
        @if (isset($actions))
            <div class="card-footer">
                {{ $actions }}
            </div>
        @endif
    </form>
</div>
