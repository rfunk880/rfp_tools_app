<x-app-layout title="View Messages">

    <div class="container-fluid flex-grow-1 container-p-y">

        <div class="card">
            <div class="card-body">
                <div class="card-title header-elements">
                    <h5 class="m-0 me-2">Subject: <strong>{{ $message->subject }}</strong></h5>
                    <div class="card-title-elements ms-auto">
                        <a href="{{ sysRoute('messages.index') }}"
                           class="btn btn-secondary btn-sm">Go Back</a>
                    </div>
                </div>
                <div>
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label for="subject">Project</label>
                            <br />
                            {{ @$message->project->name }}
                        </div>
                        <div class="col-md-6">
                            <label for="type">Message Type</label>
                            <br />
                            {!! @\App\Models\Message::$typeLabel[$message->type] !!}
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-6">
                            <label for="contact_id[]">TO:</label>
                            <br />
                            {{ $message->recepients->pluck('email')->join(', ') }}
                            {!! @$message->metadata['extra_emails'] ? ', ' . implode(', ', $message->metadata['extra_emails']) : '' !!}

                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-6">
                            <label for="user_id[]">CC:</label>
                            <br />
                            {{ $message->ccUsers->pluck('email')->join(', ') }}
                            {{-- @foreach ($message->ccUsers as $user)

        					@endforeach --}}
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="content">Message Body</label>
                            <br />
                            {!! $message->content !!}
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-6">
                            <label for="files[]">Attachments</label>
                            <br />
                            @foreach ($message->getMedia('attachments') as $media)
                                <a href="{{ route('media.download', encryptIt($media->id)) }}"
                                   target="_blank">{{ $media->name }} | {{ $media->human_readable_size }}</a><br />
                            @endforeach
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="content">Logged At</label>
                            <br />
                            {{ $message->project->projectDate($message->created_at) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
