<?php

namespace App\Livewire\Projects;

use Livewire\Component;
use App\Models\Facility as FacilityModel;

class Facility extends Component
{
    public $form = [
        'id' => '',
        'name' => '',
        'owner' => '',
        'location' => '',
        'is_key_account' => 0
    ];


    // public ?FacilityModel $facility;
    public $facilitiesOptions = [];
    public $facilityId = null;
    public $onlyAdd = false;

    public function mount($facilityId, $onlyAdd = false){
        $this->onlyAdd = $onlyAdd;
        // dd($facilityId);
        if($facilityId){
            // $this->facility = FacilityModel::findOrFail($facilityId);
            $this->facilityId = $facilityId;
        }

        $this->init();
    }

    public function init(){
        $this->facilitiesOptions = FacilityModel::all();
        $this->form = [
            'id' => '',
            'name' => '',
            'owner' => '',
            'location' => '',
            'is_key_account' => 0
        ];
    }


    public function addFacility(){
        $this->validate([
            'form.name' => 'required|unique:facilities,name',
            'form.owner' => 'required',
            'form.location' => 'required',
            'form.is_key_account' => 'required|boolean'
        ]);

        
        $this->facilityId = FacilityModel::create($this->form)->id;
        $this->init();
        $this->dispatch('update-facility', facilityId: $this->facilityId);
        $this->dispatch('close');
        
    }

    public function updatedFacilityId($value){
        // dd($value);
        $this->dispatch('update-facility', facilityId: $value);
        
    }


    public function getSelectedFacilityProperty(){
        return $this->facilityId ? FacilityModel::find($this->facilityId) : null;
    }

    public function render()
    {
        return view('webpanel.projects.components.facilities.index');
    }
}
