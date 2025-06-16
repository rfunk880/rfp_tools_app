<?php

namespace App\Livewire\Videos;

use App\Models\Video;
use Livewire\Component;
use App\Models\Subtitle as SubtitleModel;

class Subtitle extends Component
{
    public Video $video;
    public ?SubtitleModel $subtitle;

    public $form = [
        'format' => 'json'
    ];

    public function mount(Video $video)
    {
        $this->video = $video;
        $this->subtitle = $this->video->subtitle;
    }


    public function updatedFormFormat($val)
    {
        // dd($val);
    }

    public function download()
    {
        return response()->streamDownload(function () {
            if ($this->form['format'] == 'json') {
                echo json_encode($this->subtitle->transcript);
            } else {
                echo $this->subtitle->generate($this->form['format']);
            }
        }, 'subtitle.' . $this->form['format']);
    }


    public function render()
    {
        return view('livewire.videos.subtitle');
    }
}
