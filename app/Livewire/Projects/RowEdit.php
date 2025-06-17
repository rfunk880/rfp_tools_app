<?php

namespace App\Livewire\Projects;

use App\Models\Project;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class RowEdit extends Component
{
    use LivewireAlert;
    public Project $project;

    public $form = [
        'estimators' => [],
        'salesPersons' => [],
    ];

    public $rules = [
        'project.name' => 'required',
        'project.pn' => 'nullable',
        'project.facility_id' => 'nullable',
        'project.site_visit' => 'nullable',
        'project.bid_due' => 'nullable',
        'project.subcontractor_bid_due' => 'nullable',
        'project.awarded_date' => 'nullable',
        'project.bid_document' => 'nullable',
        'project.status' => 'nullable',
        'project.sales_person_id' => 'nullable',
        'project.final_estimate' => 'nullable',
        'project.po_status' => 'nullable',
        'project.est_start_date' => 'nullable',
        'project.est_end_date' => 'nullable',
        'project.duration' => 'nullable',
        'project.public_notes' => 'nullable',
        'project.internal_notes' => 'nullable',
        'project.is_key_account' => 'nullable',
        'project.probability' => 'nullable',
        // 'project.estimators' => 'nullable',
    ];

    public $timezone_offset;


    public function mount(Project $project)
    {
        $this->project = $project;
        $this->init();

    }

    public function init()
    {
        $this->timezone_offset = @$this->project->metadata['timezone'];
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
    }

    private function beforeSaving()
    {
        // dd($this->timezone_offset);
        foreach (['bid_due', 'site_visit', 'subcontractor_bid_due'] as $dateCol) {
            if ($val = $this->project->{$dateCol}) {
                $this->project->{$dateCol} = toMysqlDateTime($val, 'm-d-Y H:i', 'Y-m-d H:i:00', $this->timezone_offset);
            }
        }
    }

    public function updatedProject()
    {
        foreach (['bid_due', 'site_visit', 'est_start_date', 'est_end_date', 'subcontractor_bid_due', 'awarded_date'] as $dateCol) {
            if (!$this->project->{$dateCol}) {
                $this->project->{$dateCol} = null;
            }
        }

        $this->beforeSaving();

        $this->project->final_estimate = stringToDecimal($this->project->final_estimate);

        $this->project->save();
        $this->initProjectDates();
        $this->alert('success', 'Project updated successfully');
    }

    public function render()
    {
        return view('webpanel.projects.components.edit.row-edit');
    }
}
