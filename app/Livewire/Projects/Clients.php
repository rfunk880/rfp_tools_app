<?php

namespace App\Livewire\Projects;

use App\Models\Company;
use App\Models\Contact;
use Livewire\Component;
use App\Models\Facility as FacilityModel;
use App\Models\Project;
use Illuminate\Database\Eloquent\Collection;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\Computed;

class Clients extends Component
{
    use LivewireAlert;

    public $clientId = null;
    public $removing = null;

    public $activeClientId = null;
    public $primaryContactId = null;

    public $primaryContacts = [];


    protected $listeners = [
        'removeConfirmed' => 'confirmRemove'
    ];


    public Project $project;

    public Collection $options;


    public function mount(Project $project)
    {

        $this->init();
    }

    public function init()
    {
        $this->project->load('clients.contacts');
        // $selectedClientIds = $this->project->clients->pluck('client_id')->toArray();
        $this->options = Company::/* whereNotIn('id', count($selectedClientIds) ? $selectedClientIds : [])
        -> */whereIn('type', [\App\Models\Company::TYPE_CLIENT, \App\Models\Company::TYPE_SUBCONTRACTOR])
            ->get();
        // dd($this->options);
        
    }


    public function remove($id)
    {
        $this->removing = $id;
        $this->confirm('Are you sure you want to remove this client from the project?', [
            'showDenyButton' => false,
            // 'showCancelButton' => false,

            'cancelButtonText' => 'Cancel',
            'confirmButtonText' => 'Yes',
            'onConfirmed' => 'removeConfirmed',
            'onDenied' => null
        ]);
        return;
    }


    public function confirmRemove($id)
    {
        if ($id) {

            // $id = $this->removing;
            $this->project->clients()->detach($id);
            $this->project = $this->project->fresh();
            // $this->init();
            $this->dispatch('close');


            // $this->alert('success', 'Cilent Removed');
        }
    }


    public function updatedClientID($value)
    {

        // dd($value);
        if ($value && !$this->project->clients()->where('client_id', $value)->count()) {
            $this->project->clients()->attach($value);
            $this->project = $this->project->fresh();

            $this->init();
        }
        $this->clientId = null;
        $this->primaryContacts = null;


        $this->dispatch('close');
    }

    public function updatedPrimaryContactId($value){
        // dd($value);
        $this->project->clients()->updateExistingPivot($this->activeClientId, [
            'primary_contact_id' => $value
        ]);
        $this->init();
    }


    public function updatedPrimaryContacts($value){
        // dd($this->primaryContacts);
        $this->project->clients()->updateExistingPivot($this->activeClientId, [
            'primary_contacts' => collect($this->primaryContacts)->map(function($id){
                return Contact::where('id', $id)->select('id', 'name', 'email', 'phone')->first()->toArray();
            })->toArray()
        ]);
        $this->init();
    }

    public function toggleActiveCompany($id){
        $this->activeClientId = $id;
        $selected = $this->selectedCompany();
        // $this->primaryContactId = $selected ? @$selected->pivot->primary_contact_id : null;
        $this->primaryContacts = $selected && $selected->pivot ? @$selected->pivot->primaryContacts()->map(function($item){
            return @$item['id'];
        })->toArray() : [];
        // dd($selected->pivot->primaryContacts()->map(function($item){
        //     return @$item['id'];
        // })->toArray());
    }

    public function getAvailableClientOptionsProperty()
    {
        $clientIds = $this->project->clients()->pluck('client_id')->toArray();
        return $this->options->filter(function ($item) use ($clientIds) {
            return !in_array($item->id, $clientIds);
        });
    }

    #[Computed]
    public function selectedCompany()
    {
        return $this->project->clients->filter(function ($client) {
            return $client->id == $this->activeClientId;
        })->first();
    }


    public function render()
    {
        return view('webpanel.projects.components.clients');
    }
}
