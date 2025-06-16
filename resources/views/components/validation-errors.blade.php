@if ($errors->any())
    <div class="alert alert-danger">
        {{-- <h4 class="font-medium text-red-600">{{ __('Whoops! Something went wrong.') }}</h4> --}}

        <ul class="list-disc list-inside text-sm text-red-600 pb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
