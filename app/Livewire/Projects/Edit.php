<?php

namespace App\Livewire\Projects;

use App\Models\Project;
use App\Services\CalendarService;
use App\Services\GoogleCalendarService;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\Attributes\On;

class Edit extends Component
{
    use LivewireAlert;

    public $timezone_offset;

    public $form = [
        'estimators' => [],
        'salesPersons' => [],
    ];

    public Project $project;
    public ?Project $next;
    public ?Project $previous;

    public $rules = [
        'project.name' => 'required',
        'project.pn' => 'nullable',
        'project.facility_id' => 'nullable',
        'project.site_visit' => 'nullable',
        'project.bid_due' => 'nullable',
        'project.subcontractor_bid_due' => 'nullable',
        'project.bid_document' => 'nullable',
        'project.status' => 'nullable',
        'project.sales_person_id' => 'nullable',
        'project.final_estimate' => 'nullable',
        'project.po_status' => 'nullable',
        'project.est_start_date' => 'nullable',
        'project.est_start_date' => 'nullable',
        'project.est_end_date' => 'nullable',
        'project.duration' => 'nullable',
        'project.public_notes' => 'nullable',
        'project.internal_notes' => 'nullable',
        // 'project.is_key_account' => 'nullable',
        'project.probability' => 'nullable',
        // 'project.estimators' => 'nullable',
        'project.awarded_date' => 'nullable',
    ];

    public function mount($id)
    {
        if (!canAdd()) {
            abort(404);
        }
        $this->project = Project::findOrFail(decryptIt($id));
        $this->previous = Project::where('pn', '<', $this->project->pn)
            ->orderBy('pn', 'desc')
            ->first();

        $this->next = Project::where('pn', '>', $this->project->pn)
            ->orderBy('pn', 'asc')
            ->first();
        // dd($this->project);
        $this->init();
        // dd($this->form);
    }

    public function init()
    {
        $this->project->is_key_account = (bool) $this->project->is_key_account;
        $this->project->final_estimate = (int) $this->project->final_estimate;
        $this->project->probability = (int) $this->project->probability;
        $this->initProjectDates();
        $this->form['estimators'] = $this->project->estimators->pluck('id')->toArray();
        $this->form['salesPersons'] = $this->project->salesPersons->pluck('id')->toArray();
    }

    public function initProjectDates()
    {
        foreach (['bid_due', 'site_visit', 'subcontractor_bid_due'] as $dateCol) {
            if ($val = $this->project->{$dateCol}) {
                $this->project->{$dateCol} = toTimezoneDate($val, 'm-d-Y H:i', $this->timezone_offset ?? @$this->project->metadata['timezone']);
            }
        }
        foreach (['est_start_date', 'est_end_date', 'awarded_date'] as $dateCol) {
            if ($val = $this->project->{$dateCol}) {
                $this->project->{$dateCol} = toAppDate($val);
            }
        }
        // dd($this->project->awarded_date);
    }


    public function updatedProject($value)
    {
        // *** FIX: This method is intentionally left empty. ***
        // It was causing an automatic save that conflicted with the main "Save" button,
        // which erased the awarded_date before it could be saved properly.
        // All saving is now handled by the saveProject() method.
    }

    private function beforeSaving()
    {
        // dd($this->timezone_offset);
        foreach (['bid_due', 'site_visit', 'subcontractor_bid_due'] as $dateCol) {
            if ($val = $this->project->{$dateCol}) {
                $this->project->{$dateCol} = toMysqlDateTime($val, 'm-d-Y H:i', 'Y-m-d H:i:00', $this->timezone_offset);
            }
        }

        $dirty = $this->project->getDirty();
        if(isset($dirty['po_status']) && $dirty['po_status'] == Project::STATUS_PO_AWARDED && !isset($dirty['awarded_date'])){
            $this->project->awarded_date = now();
            // $this->project->save();
        }

        if($this->project->awarded_date && $this->project->po_status != Project::STATUS_PO_AWARDED){
            $this->project->awarded_date = null;
        }


        foreach (['est_start_date', 'est_end_date', 'awarded_date'] as $dateCol) {
            if ($val = $this->project->{$dateCol}) {
                $this->project->{$dateCol} = toMysqlDate($val);
            }
        }
        // dd($this->project->toArray());
        // if(!$this->project->probability){
        //     $this->project->probability = 40;
        // }
    }

    public function saveProject()
    {
        // This loop ensures that if a date is cleared in the form, it is correctly set to null before saving.
        foreach (['bid_due', 'site_visit', 'est_start_date', 'est_end_date', 'awarded_date', 'subcontractor_bid_due'] as $dateCol) {
            if (empty($this->project->{$dateCol})) {
                $this->project->{$dateCol} = null;
            }
        }

        $this->beforeSaving();
        
        $this->project->final_estimate = stringToDecimal($this->project->final_estimate);

        $this->project->save();
        
        try {

            $service = new GoogleCalendarService($this->project->fresh());
            $service->update();
            $this->alert('success', 'Project Saved');
        } catch (\Exception $e) {
            // dd($e);
            $this->alert('error', 'Project couldn\'t be updated to calendar ' . $e->getMessage());
        }
        
        $this->initProjectDates();
    }

    public function updatedFormEstimators($val, $key)
    {
        // dd([$this->form['estimators']]);

        if ($this->form['estimators'] && count($this->form['estimators'])) {
            $this->project->estimators()->detach();
            $this->project->estimators()->attach($this->form['estimators'], [
                'type' => 'estimators'
            ]);
            $this->alert('success', 'Project Saved');
            $this->dispatch('close');
        }
    }

    public function updatedFormSalesPersons($val, $key)
    {
        // dd([$this->form['estimators']]);

        if ($this->form['salesPersons'] && count($this->form['salesPersons'])) {
            $this->project->salesPersons()->detach();
            $this->project->salesPersons()->attach($this->form['salesPersons'], [
                'type' => 'sales'
            ]);
            $this->alert('success', 'Project Saved');
            $this->dispatch('close');
        }
    }

    #[On('update-facility')]
    public function facilityUpdated($facilityId)
    {
        if ($facilityId) {

            $this->beforeSaving();
            // dd($facilityId);
            $this->project->facility_id = $facilityId;
            // $this->beforeSaving();

            $this->project->save();
            $this->project = $this->project->fresh();
            $this->initProjectDates();
        }
    }


    public function updatedTimezoneOffset($val)
    {
        // dd($val);
        if (is_null($this->project->metadata) || is_null($this->project->metadata['timezone'])) {
            $this->beforeSaving();

            $this->project->updateJsonField([
                'timezone' => $val
            ], 'metadata');
        }
        $this->project = $this->project->fresh();
        $this->init();
        // $this->initProjectDates();
    }

    public function render()
    {
        return view('webpanel.projects.components.edit.index')
            ->layout('layouts.app', ['title' => 'Project Edit']);
    }
}
