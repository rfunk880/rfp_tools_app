<div>
    @if ($onlyAdd)
        <a
            href="#"
            data-bs-toggle="modal"
            data-bs-target="#facilityModal"
            class="btn btn-sm btn-dark ms-auto"
        ><span class="tf-icon ti ti-plus ti-xs me-1"></span>New
            Facility</a>
    @else
        <div class="card">
            <div class="card-header d-flex align-items-center fw-bold">Facility
                <div class="d-flex ms-auto">

                    <a
                        href="{{ sysRoute('facilities.index') }}"
                        class="btn btn-sm btn-dark me-2 ms-auto"
                    >Facility Mgmt</a>
                    <a
                        href="#"
                        data-bs-toggle="modal"
                        data-bs-target="#facilityModal"
                        class="btn btn-sm btn-dark ms-auto"
                    ><span class="tf-icon ti ti-plus ti-xs me-1"></span>New
                        Facility</a>
                </div>
            </div>
            <div class="card-body">
                <div wire:ignore.self>
                    <select
                        id="facilityId"
                        class="form-select"
                        wire:model.live="facilityId"
                    >
                        <option value=""></option>
                        {!! OptionsView($facilitiesOptions, 'id', function ($item) {
                            return $item->name /*  . ', ' . $item->owner */;
                        }) !!}
                    </select>
                </div>

                @if ($this->selectedFacility)
                    <div class="bg-light mt-2 p-2">
                        <p class="mb-0">
                            <strong>Name: </strong> {{ @$this->selectedFacility->name }}<br />
                            <strong>Owner: </strong> {{ @$this->selectedFacility->owner }}<br />
                            <strong>Location: </strong> {{ @$this->selectedFacility->location }}
                        </p>
                    </div>
                @endif
            </div>
        </div>
    @endif

    @teleport('#stack-modals')
        <div
            id="facilityModal"
            class="modal fade"
            tabindex="-1"
            role="dialog"
            aria-labelledby="modalTitleId"
            wire:ignore.self
            aria-hidden="true"
        >
            <div
                class="modal-dialog"
                role="document"
            >
                <div class="modal-content">
                    <form
                        method="post"
                        wire:submit.prevent="addFacility"
                    >
                        @csrf
                        <div class="modal-header">
                            <h5
                                id="modalTitleId"
                                class="modal-title"
                            >
                                Add New Facility
                            </h5>
                            <button
                                type="button"
                                class="btn-close"
                                data-bs-dismiss="modal"
                                aria-label="Close"
                            ></button>
                        </div>
                        <div class="modal-body">
                            <div id="project-create-notification"></div>
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <label for="name">Facility Name*</label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        name="name"
                                        wire:model.defer="form.name"
                                    >
                                    <x-input-error for="form.name" />
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-12">
                                    <label for="owner">Owner</label>
                                    <input
                                        id="owner"
                                        type="text"
                                        class="form-control"
                                        name="owner"
                                        wire:model.defer="form.owner"
                                    >
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-12">
                                    <label for="location">Address</label>
                                    <input
                                        id="location"
                                        type="text"
                                        class="form-control"
                                        name="location"
                                        wire:model.defer="form.location"
                                    >
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <label for="is_key_account">&nbsp;</label><br />
                                    <input type="hidden" name="is_key_account" value="0" wire:model.lazy="form.is_key_account">
                                    <div class="form-check form-switch mb-2">
                                        <input
                                            id="is_key_account"
                                            class="form-check-input"
                                            type="checkbox"
                                            name="is_key_account"
                                            wire:model.lazy="form.is_key_account"
                                        />
                                        <label
                                            class="form-check-label"
                                            for="is_key_account"
                                        >Is Key Account</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button
                                type="button"
                                class="btn btn-secondary"
                                data-bs-dismiss="modal"
                            >Close</button>
                            <button
                                type="submit"
                                class="btn btn-dark"
                            >Save @if (!$onlyAdd)
                                    and Select
                                @endif
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endteleport
</div>

@script
    <script>
        $(function() {
            initFacilitySelect2();
        });

        function initFacilitySelect2() {
            $("#facilityId").select2({
                placeholder: "Select Facility"
            }).on('change', function(e) {
                $wire.set('facilityId', e.target.value);
            });
        }
        $wire.on('close', (e) => {
            console.log($wire.facilityId.toString());
            const $selected = $wire.facilityId;
            setTimeout(() => {
                if ($selected) {
                    $("#facilityId").val($selected);
                }
                // initFacilitySelect2();
            }, 300);
            // $(".select2").select2();
            // $("#facilityId").trigger('change');
            // console.log('close called');
            hideModal($('#facilityModal'));
        });
    </script>
@endscript
