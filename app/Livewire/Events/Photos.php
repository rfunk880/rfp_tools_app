<?php

namespace App\Livewire\Events;

use Livewire\Component;
use App\Models\Event;
use Illuminate\Support\Facades\DB;
use Livewire\WithFileUploads;
use Support\Models\Attachment;
use Support\Traits\UploaderTrait;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class Photos extends Component
{
    use WithFileUploads;
    use UploaderTrait;
    use LivewireAlert;

    public $files = [];
    public $attachments = [];
    public $event;
    public $editable = true;

    public $children = [
        [
            'name' => '',
            'dob' => ''
        ]
    ];

    public function mount(Event $event)
    {
        $this->event = $event;
        $this->refreshFiles();
        // $this->refreshChildren();
    }

    

   

    public function refreshFiles()
    {
        $this->attachments = $this->event->attachments()->get();
    }

    public function updatedFiles()
    {
        if (!$this->editable) {
            return;
        }
        foreach ($this->files as $file) {
            $attachment = $this->setUploadType('profile')
                // ->setTitle(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME))
                ->uploadMedia(Attachment::pathToUploadedFile($file->path(), $file->getClientOriginalName()));
            $this->event->attachments()->save($attachment);
        }

        $this->refreshFiles();
        // You can do whatever you want to do with $this->files here
    }

    public function removeMedia($id, $index = null)
    {
        $media = Attachment::findOrFail($id);
        $media->selfDestruct(true);
        // unset($this->files[$index]);
        $this->refreshFiles();
        $this->alert("success", 'Removed');
    }

    

    public function render()
    {
        return view('livewire.events.photos');
    }
}
