<div>
    <h4 class="fw-bold m-0 mb-5">Profile Pictures</h4>

    <div x-data="drop_file_component()"
         x-on:livewire-upload-start="console.log('upload started'); uploading = true"
         x-on:livewire-upload-finish="uploading = false"
         x-on:livewire-upload-error="uploading = false"
         x-on:livewire-upload-progress="progress = $event.detail.progress">
        <div class="flex h-screen items-center justify-center">
            <div style="display:flex; flex-direction:column; justify-content: center; align-items: center; padding:2rem; border:1px dashed gray;"
                 x-bind:class="dropingFile ? 'bg-gray-400 border-gray-500' : 'border-gray-500 bg-gray-200'"
                 x-on:drop="dropingFile = false; uploading=true;"
                 x-on:drop.prevent="
            handleFileDrop($event)"
                 x-on:dragover.prevent="dropingFile = true"
                 x-on:dragleave.prevent="dropingFile = false">

                <i class="flaticon-upload fa-5x"></i>
                <h2 class="text-center font-weight-bold fw-bolder" wire.target="files">Drop Your Files Here</h2>
                <form wire:submit="save" class="mb-2 mt-4 text-center">
                    <div>
                        <div class="mb-3">
                            <input multiple type="file" wire:model="files" />
                        </div>
                        <div x-show="uploading">
                            <progress max="100" x-bind:value="progress"></progress>
                        </div>
                    </div>

                    @error('files')
                        <p class="text-danger">{{ $message }}</p>
                    @enderror
                </form>
            </div>
        </div>

        <div class="mt-4">
            @if (count($attachments))

                <div class="overflow-auto pb-5">
                    <div class="d-flex align-items-center min-w-700px p-7">
                @foreach ($attachments as $index => $file)
                <div class="overlay me-10 mr-3 ml-2">
                    <div class="overlay-wrapper border border-active">
                        <img alt="img" class="w-150px" src="{{ $file->getThumbUrl() }}" title="{{ @$file->media->original_name }}" alt="{{ @$file->media->original_name }}">
                    </div>
                    <div class="overlay-layer p-2">
                        <a href="{{ $file->getFileUrl() }}" class="btn btn-sm btn-primary mr-1" target="_blank"><i class="fa fa-eye"></i></a>
                        {{--  --}}
                        @if ($editable)
                            <a href="#" wire:click.prevent="removeMedia({{ $file->id }})" wire:confirm="Are you sure you want to delete this item?"
                               class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></a>
                        @endif
                    </div>
                </div>
                @endforeach
                    </div>
                </div>
            @else
                <p class="fs-3 fw-bold text-danger">No files uploaded for profile pictures.</p>
            @endif
        </div>
    </div>
</div>
        

<script>
    function drop_file_component() {
        return {
            dropingFile: false,
            uploading: false,
            progress: 0,
            handleFileDrop(e) {
                if (event.dataTransfer.files.length > 0) {
                    const files = e.dataTransfer.files;
                    @this.uploadMultiple('files', files,
                        (uploadedFilename) => {}, () => {
                        }, (event) => {
                            // console.log(this.uploading);
                            if (event.detail.progress >= 100) {
                                this.uploading = false;
                            }
                            // console.log(event);
                        }
                    )
                }
            }
        };
    }
</script>