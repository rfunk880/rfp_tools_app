<tr>
    <td style="width:350px;">
        <x-input-toggle value="{{ $project->name }}">
            <input id="name"
                   type="text"
                   class="form-control"
                   name="name"
                   wire:model.lazy="project.name" />
        </x-input-toggle>
    </td>
    
    <td style="width:250px;">
            <x-datetime id="site_visit_{{ $project->id }}"
                        type="text"
                        class="form-control"
                        name="site_visit"
                        wire:model.live="project.site_visit" />
    </td>
    
    <td style="width:250px;">
            <x-datetime id="bid_due_{{ $project->id }}"
                        type="text"
                        class="form-control"
                        name="bid_due"
                        wire:model.live="project.bid_due" />
  
    </td>
    
    <td style="width:250px;">
            <x-datetime id="subcontractor_bid_due_{{ $project->id }}"
                        type="text"
                        class="form-control"
                        name="subcontractor_bid_due"
                        wire:model.live="project.subcontractor_bid_due" />
  
    </td>
    
    <td style="width:250px;">
        <x-input-toggle value="{{ $project->bid_document }}">
            <input id="bid_document"
                   type="text"
                   class="form-control"
                   name="bid_document"
                   wire:model.lazy="project.bid_document">
        </x-input-toggle>
    </td>
     
    <td style="width:250px;">
        <x-input-toggle value="{{ $project->est_start_date }}">
            <x-date id="est_start_date_{{ $project->id }}"
                    type="text"
                    class="form-control"
                    name="est_start_date"
                    wire:model.live="project.est_start_date" />
        </x-input-toggle>
    </td>
    
    <td style="width:250px;">
        <x-input-toggle value="{{ $project->est_end_date }}">
            <x-date id="est_end_date_{{ $project->id }}"
                    type="text"
                    class="form-control"
                    name="est_end_date"
                    wire:model.live="project.est_end_date" />
        </x-input-toggle>
    </td>
    
    <td style="width:250px;">
        <x-input-toggle value="{{ $project->final_estimate }}">
            <input id="final_estimate"
                   type="text"
                   class="form-control money"
                   name="final_estimate"
                   style="text-align: right;"
                   wire:model.lazy="project.final_estimate">
        </x-input-toggle>
    </td>
    
    <td style="width:250px;">
        <x-input-toggle value="{{ $project->duration }}">
            <input id="duration"
                   type="number"
                   step="1"
                   class="form-control"
                   name="duration"
                   wire:model.lazy="project.duration">
        </x-input-toggle>
    </td>
    
    <td style="width:250px;">
        <x-input-toggle value="{{ $project->public_notes }}">
            <textarea id="public_notes"
            type="text"
            class="form-control editor"
            name="public_notes"
            wire:model.defer="project.public_notes"></textarea>
        </x-input-toggle>
    </td>
    
    <td style="width:250px;">
        <x-input-toggle value="{{ $project->internal_notes }}">
            <textarea id="internal_notes"
                      type="text"
                      class="form-control editor"
                      name="internal_notes"
                      value="{{ $project->internal_notes }}"
                      wire:model.lazy="project.internal_notes"></textarea>
        </x-input-toggle>
    </td>
    
    <td style="width:250px;">
        <x-input-toggle value="{{ $project->probability }}">
            <select id="probability"
                    class="form-select"
                    name="probability"
                    wire:model.lazy="project.probability">
                @for ($i = 10; $i <= 90; $i += 10)
                    <option value="{{ $i }}">{{ $i }}%</option>
                @endfor
            </select>
        </x-input-toggle>
    </td>
    
    <td style="width:250px;">
        <x-input-toggle value="{{ $project->po_status }}">
            <select id="po_status"
                    class="form-select"
                    name="po_status"
                    wire:model.lazy="project.po_status">
                <option value="0">NA (default)</option>
                {!! arrayOptions(\App\Models\Project::$poStatusLabel, (int) $project->po_status) !!}
            </select>
        </x-input-toggle>
    </td>

    <td style="width:250px;">
        <x-input-toggle value="{{ $project->awarded_date }}">
            <x-date id="awarded_date_{{ $project->id }}"
                        type="text"
                        class="form-control"
                        name="awarded_date"
                        wire:model.live="project.awarded_date" />
        </x-input-toggle>
    </td>
    
</tr>


