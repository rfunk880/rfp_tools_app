<?php

namespace App\Livewire\Projects;

use App\Models\Company;
use App\Models\Contact;
use App\Models\Project;
use Livewire\Component;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\DB;
use App\Models\Facility as FacilityModel;
use Illuminate\Database\Eloquent\Collection;
use Jantinnerezo\LivewireAlert\LivewireAlert;


class Companies extends Component
{
    use LivewireAlert;

    public $companyId = null;
    public $removing = null;

    public $activeCompanyId = null;
    public $primaryContactId = null;

    public $tags = [];

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
        $this->project = $this->project->fresh();
        // $selectedCompanyIds = $this->project->companies->pluck('company_id')->toArray();
        $builder = Company::/* whereNotIn('id', count($selectedCompanyIds) ? $selectedCompanyIds : [])
        -> */whereIn('type', [\App\Models\Company::TYPE_SUBCONTRACTOR, \App\Models\Company::TYPE_SUPPLIER]);

        $contactIds = $this->getContactsByTag();
        if (count($contactIds)) {

            $builder->whereHas('contacts', function ($q) use($contactIds) {
                return $q->whereIn('contacts.id', $contactIds);
            });
        }
        $this->options = $builder->get();
        // dd($this->options);
    }



    public function getContactsByTag()
    {
        if ($this->tags && count($this->tags)) {
            $contactIds = DB::table('taggables')->whereIn('tag_id', $this->tags)
                    ->selectRaw("COUNT(tag_id) as totalTags, taggable_id")
                    ->where('taggable_type', '=', Contact::class)
                    ->havingRaw("totalTags = " . count($this->tags))
                    ->groupBy('taggable_id')
                    ->pluck('taggable_id')
                    ->toArray();
            // return Contact::whereHas('tags', function ($q) {
            //     $q->whereIn('tags.name', $this->tags)
            //     ->groupBy('tags.id')
            //     ->havingRaw("COUNT(tags.id) = ".count($this->tags));
            // })->pluck('contacts.id')->toArray();

            return count($contactIds) ? $contactIds : [0];
        }

        return [];
    }

    public function remove($id)
    {
        $this->removing = $id;
        $this->confirm('Are you sure you want to remove this company from the project?', [
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
            $this->project->companies()->detach($id);
            $this->project = $this->project->fresh();
            // $this->init();
            $this->dispatch('close');


            // $this->alert('success', 'Cilent Removed');
        }
    }


    public function updatedCompanyID($value)
    {

        // dd($value);
        if ($value && !$this->project->companies()->where('company_id', $value)->count()) {
            $this->project->companies()->attach($value);
            $this->project = $this->project->fresh();
            // $this->init();

        }
        $this->companyId = null;
        $this->primaryContacts = null;

        $this->dispatch('close');
    }


    public function updatedTags()
    {
        $this->init();
        $this->dispatch('close');
    }

    public function getAvailableCompanyOptionsProperty()
    {
        $companyIds = $this->project->companies()->pluck('company_id')->toArray();
        return $this->options->filter(function ($item) use ($companyIds) {
            return !in_array($item->id, $companyIds);
        });
    }


    public function updatedPrimaryContactId($value){
        // dd($value);
        $this->project->companies()->updateExistingPivot($this->activeCompanyId, [
            'primary_contact_id' => $value
        ]);
        $this->init();
    }

    public function updatedPrimaryContacts($value){
        // dd($this->primaryContacts);
        $this->project->companies()->updateExistingPivot($this->activeCompanyId, [
            'primary_contacts' => collect($this->primaryContacts)->map(function($id){
                return Contact::where('id', $id)->select('id', 'name', 'email', 'phone')
                ->with(['tags' => function($q){
                    return $q->selectRaw('name');
                }])->first()->toArray();
            })->toArray()
        ]);

        $this->init();
        $this->dispatch('close');

    }


    public function toggleActiveCompany($id){
        $this->activeCompanyId = $id;
        $selected = $this->selectedCompany();
        // $this->primaryContactId = $selected ? @$selected->pivot->primary_contact_id : null;
        $this->primaryContacts = $selected && $selected->pivot ? @$selected->pivot->primaryContacts()->map(function($item){
            return @$item['id'];
        })->toArray() : [];
        $this->dispatch('close');

    }

    #[Computed]
    public function selectedCompany()
    {
        return $this->project->companies->filter(function ($client) {
            return $client->id == $this->activeCompanyId;
        })->first();
    }

    public function render()
    {
        return view('webpanel.projects.components.companies');
    }
}
