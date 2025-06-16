<x-app-layout title="View Calllogs">

    <div class="container-fluid flex-grow-1 container-p-y">

        <div class="card">
            <div class="card-body">
                <div class="card-title header-elements">
                    <h5 class="m-0 me-2">Reason: <strong>{{ $calllog->subject }}</strong></h5>
                    <div class="card-title-elements ms-auto">
                        <a href="{{ sysRoute('calllogs.index') }}" class="btn btn-secondary btn-sm">Go Back</a>
                    </div>
                </div>
                <div>
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label for="subject">Project</label>
                            <br />
                            {{ @$calllog->project->name }}
                        </div>
                        {{-- <div class="col-md-6">
                            <label for="type">Calllog Type</label>
                            <br />
                            {!! @\App\Models\Calllog::$typeLabel[$calllog->type] !!}
                        </div> --}}
                    </div>

                    <div class="form-group row">
                        <div class="col-md-6">
                            <label for="contact_id[]">TO:</label>
                            <br />
                            {!! $calllog->recepients->map(function($item)
                                {
                                    return '<span class="p-2 mb-2 me-2">'.$item->name.' ('.$item->phone.')</span><br/>'; })->join(' ') !!}
                        </div>
                    </div>

                    

                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="content">Call log</label>
                            <br />
                            {!! $calllog->content !!}
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="content">Logged At</label>
                            <br />
                            {{ $calllog->project->projectDate($calllog->created_at) }}
                        </div>
                    </div>

                    {{-- <div class="form-group row">
                        <div class="col-md-6">
                            <label for="files[]">Attachments</label>
                            <br />
                            @foreach ($calllog->getMedia('attachments') as $media)
                                <a href="{{ route('media.download', encryptIt($media->id)) }}"
                                   target="_blank">{{ $media->name }} | {{ $media->human_readable_size}}</a><br/>
                            @endforeach
                        </div>
                    </div> --}}
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
