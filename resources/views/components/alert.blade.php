@props(['message', 'type'])
<div id="notificationArea">
    @if (@$message)
        <div class="alert alert-dismissible alert-{{ @$type }} fade show mt-2" role="alert">
            <div class="d-flex flex-column pe-sm-10 pe-0">
                {{-- <h5 class="mb-1">Message</h5> --}}
                <span>{{ $message }}</span>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
</div>
