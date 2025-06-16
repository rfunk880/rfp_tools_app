{{-- 
  Full Code for: rfp_tools/resources/views/webpanel/projects/components/edit/partials/form.blade.php
  This version includes the robust fix to ensure Trix editor content is saved correctly
  by synchronizing the data just before the form submission is processed.
--}}

<form wire:submit.prevent="saveProject"
      x-data
      x-on:submit="
        $wire.set('project.public_notes', document.querySelector('#public_notes').value);
        $wire.set('project.internal_notes', document.querySelector('#internal_notes').value);
      ">
    <div class="card">
        <div class="card-header d-flex align-items-center">
            <h4 class="fw-bold mb-0">Project <span class="text-info"> {{ $project->name }}</span></h4>
            @include('webpanel.projects.components.edit.partials.status')
        </div>
        <div class="card-body"
             x-data="{ offset: getTimeZone() }">
            <input type="hidden"
                   name="timezone_offset"
                   x-model="offset"
                   wire:model="timezone_offset" />
            <div class="form-group row">
                <div class="col-md-6">
                    <label for="name">Project Name</label>
                    <input id="name"
                           type="text"
                           class="form-control"
                           name="name"
                           wire:model.lazy="project.name">
                </div>
                <div class="col-md-6">
                    <label for="pn">Project#</label><br />
                    {{ $project->pn }}
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-6">
                    <label for="site_visit">Site Visit</label>
                    <x-datetime id="site_visit"
                                type="text"
                                class="form-control"
                                name="site_visit"
                                wire:model.live="project.site_visit" />
                </div>

                <div class="col-md-6">
                    <label for="bid_due">Bid Due</label>
                    <x-datetime id="bid_due"
                                type="text"
                                class="form-control"
                                name="bid_due"
                                wire:model.live="project.bid_due" />
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-6">
                    <label for="subcontractor_bid_due">Subcontractor Bid Due</label>
                    <x-datetime id="subcontractor_bid_due"
                                type="text"
                                class="form-control"
                                name="subcontractor_bid_due"
                                wire:model.live="project.subcontractor_bid_due" />
                </div>

                <div class="col-md-6">
                    <label for="bid_document">Bid Document</label>
                    <input id="bid_document"
                           type="text"
                           class="form-control"
                           name="bid_document"
                           wire:model.lazy="project.bid_document">
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-6"
                     wire:ignore>
                    <label for="salesPersons">Sales Person</label>
                    <select id="salesPersons"
                            class="form-select"
                            name="salesPersons"
                            multiple
                            wire:model.lazy="form.salesPersons">
                        <option value=""></option>
                        {!! OptionsView(\App\Models\User::exceptAdmin()->active()->get(), 'id', 'name') !!}
                    </select>
                </div>

                <div class="col-md-6"
                     wire:ignore>
                    <label for="estimators">Estimators</label>
                    <select id="estimators"
                            class="form-select"
                            name="estimators"
                            multiple
                            wire:model.lazy="form.estimators">
                        <option value=""></option>
                        {!! OptionsView(\App\Models\User::exceptAdmin()->active()->get(), 'id', 'name') !!}
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-6">
                    <label for="est_start_date">Estimated Start Date</label>
                    <x-date id="est_start_date"
                            type="text"
                            class="form-control"
                            name="est_start_date"
                            wire:model.live="project.est_start_date" />
                </div>

                <div class="col-md-6">
                    <label for="est_end_date">Estimated End Date</label>
                    <x-date id="est_end_date"
                            type="text"
                            class="form-control"
                            name="est_end_date"
                            wire:model.live="project.est_end_date" />
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-6">
                    <label for="final_estimate">Final Estimate</label>
                    <input id="final_estimate"
                           type="text"
                           class="form-control money"
                           name="final_estimate"
                           style="text-align: right;"
                           wire:model.lazy="project.final_estimate">
                </div>

                <div class="col-md-6">
                    <label for="duration">Duration (days)</label>
                    <input id="duration"
                           type="number"
                           step="1"
                           class="form-control"
                           name="duration"
                           wire:model.lazy="project.duration">
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-6" wire:ignore>
                    <label for="public_notes">Public Notes</label>
                    <input id="public_notes" type="hidden" name="public_notes" value="{{ $project->public_notes }}">
                    <trix-editor input="public_notes" style="min-height: 250px;"></trix-editor>
                </div>

                <div class="col-md-6" wire:ignore>
                    <label for="internal_notes">Internal Notes</label>
                    <input id="internal_notes" type="hidden" name="internal_notes" value="{{ $project->internal_notes }}">
                    <trix-editor input="internal_notes" style="min-height: 250px;"></trix-editor>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-4">
                    <label for="probability">Probability</label>
                    <select id="probability"
                            class="form-select"
                            name="probability"
                            wire:model.lazy="project.probability">
                        @for ($i = 10; $i <= 90; $i += 10)
                            <option value="{{ $i }}">{{ $i }}%</option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="po_status">PO Status</label>
                    <select id="po_status"
                            class="form-select"
                            name="po_status"
                            wire:model.lazy="project.po_status">
                        <option value="0">NA (default)</option>
                        {!! arrayOptions(\App\Models\Project::$poStatusLabel, (int) $project->po_status) !!}
                    </select>
                </div>
                    @if($project->po_status == \App\Models\Project::STATUS_PO_AWARDED)
                        <div class="col-md-4">
                    <label for="awarded_date">Awarded Date</label>
                    <x-date id="awarded_date"
                            type="text"
                            class="form-control"
                            name="awarded_date"
                            wire:model.lazy="project.awarded_date"/>
                </div>
                    @endif
                
            </div>

        </div>
        <div class="card-footer text-muted">
            <x-button type="submit" target="saveProject"  loading="Saving...">Save</x-button>
        </div>
    </div>
</form>

@push('styles')
<style>
    trix-editor {
        min-height: 250px;
    }
</style>
@endpush

@script
    <script>
        $(function() {
            initEstimatorSelect2();
            $wire.set('timezone_offset', getTimeZone());
        });

        function initEstimatorSelect2() {
            $("#estimators").select2({
                placeholder: "Select Estimators",
                allowClear: true
            }).on('change', function(e) {
                $wire.set('form.estimators', $("#estimators").val());
            });

            $("#salesPersons").select2({
                placeholder: "Select Sales Persons",
                allowClear: true
            }).on('change', function(e) {
                $wire.set('form.salesPersons', $("#salesPersons").val());
            });

            $(".money").inputmask({
                alias: 'decimal',
                groupSeparator: ','
            });

            $(".money").on('change', function(e) {
                $wire.set('project.final_estimate', e.target.value);
            });
        }
        $wire.on('close', (e) => {
            setTimeout(() => {
                initEstimatorSelect2();
            }, 300);
        });
    </script>
@endscript
