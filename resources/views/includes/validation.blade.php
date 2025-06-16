@if (@$errors && $errors->any())

    <div class="alert alert-danger alert-dismissible" role="alert">
        <h5 class="alert-heading mb-2">Validation Error</h5>
        <p class="mb-0">
        <ul class="mb-0 pb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        </p>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>

@endif
