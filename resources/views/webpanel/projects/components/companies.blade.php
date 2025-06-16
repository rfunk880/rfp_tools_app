<div class="card">
    <div class="card-header d-flex align-items-center fw-bold">
        Vendors <a class="ms-auto" title="Add New" href="{{ sysRoute('companies.create')}}?context=companies&project_id={{ encryptIt($project->id)}}">
            <span class="tf-icon ti ti-plus ti-xs me-1"></span>
        </a>
    </div>

    <div class="card-body position-relative">
        <div id="loading"
             style="position:absolute; top:0; left:0; width:100%; height:100%; z-index:1000;"
             class="d-none justify-content-center align-items-center bg-white">
            LOADING
        </div>

        <select id="company-tags"
                multiple
                class="form-select">
            <option value=""></option>
            {!! OptionsView(\App\Models\Tag::select('name', 'id')->get(), 'id', 'name') !!}
        </select>

        @if ($options->count())
            <p>Found <span class="text-info fw-bold">{{ $options->count() }}
                </span> total vendors.</p>
            {{-- @dd($this->availableCompanyOptions) --}}
            <div wire:ignore.self>
                <select id="companyId"
                        class="form-select"
                        wire:model.live="companyId">
                    <option value=""></option>
                    {!! OptionsView($this->availableCompanyOptions, 'id', function ($item) {
                        return $item->name /*  . ', ' . $item->owner */;
                    }) !!}
                </select>
            </div>
        @else
            @if (count($tags))
                <x-alert message="No Vendor Found"
                         type="info" />
            @endif
        @endif
        @if ($project->companies->count())
            <ul class="list-group mt-4">
                @foreach ($project->companies as $company)
                    <li class="list-group-item w-full">
                        <div class="d-flex">
                            <a href="{{ sysRoute('companies.show', encryptIt($company->id)) }}">
                                {{ $company->name }}</a>
                            <a class="ms-auto"
                               href="#"
                               wire:click.prevent="confirmRemove({{ $company->id }})">Remove</a>
                        </div>
                        <div class="mt-1 w-full">
                            <label class="cursor"
                                   role="button"
                                   title="Select Primary Contact"
                                   wire:click="toggleActiveCompany({{ $company->id }})"
                                   data-bs-toggle="modal"
                                   data-bs-target="#companyPrimaryContactModal">
                                @if (@$company->pivot->primary_contacts && is_array(@$company->pivot->primary_contacts))
                                    @foreach (@$company->pivot->primary_contacts as $contact)
                                        <span>{{ @$contact['name'] }} </span>
                                        @if (@$contact['tags'] && is_array($contact['tags']))
                                            -
                                            @foreach ($contact['tags'] as $tag)
                                                <span
                                                      class="bg-secondary m2-2 rounded px-2 py-1 text-white">{{ $tag['name'] }}</span>
                                            @endforeach
                                        @else
                                            {{ @$contact['email'] }}
                                        @endif
                                        <br />
                                    @endforeach
                                @else
                                    <span>{{ $company->contacts->count() ? 'Select Primary Contact' : 'No Contacts' }}</span>
                                @endif

                            </label>

                        </div>
                    </li>
                @endforeach
            </ul>
        @else
            <x-alert type="info"
                     message="No companies added." />
        @endif
    </div>
    @teleport('#stack-modals')
        <div id="companyPrimaryContactModal"
             class="modal fade"
             tabindex="-1"
             role="dialog"
             aria-labelledby="modalTitleId"
             wire:ignore.self
             aria-hidden="true">
            <div class="modal-dialog"
                 role="document">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 id="modalTitleId"
                            class="modal-title">
                            Primary Contact For {{ @$this->selectedCompany->name }}
                        </h5>
                        <button type="button"
                                class="btn-close"
                                data-bs-dismiss="modal"
                                aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @if ($this->selectedCompany && $this->selectedCompany->contacts()->count())
                            <ul class="list-group"
                                x-data="{}">
                                @foreach ($this->selectedCompany->contacts()->with('tags')->get() as $contact)
                                    <li class="list-group-item">
                                        <label>
                                            <input id="project_company_primary_contact_checkbox_{{ $contact->id }}"
                                                   type="checkbox"
                                                   x-on:change="onCompanyContactIdChange"
                                                   class="project_company_primary_contacts"
                                                   name="project_company_primary_contact"
                                                   {!! isMultiChecked($contact->id, $primaryContacts) !!}
                                                   value="{{ $contact->id }}">
                                            <span class="ms-2">{{ $contact->name }}</span>
                                            @if (@$contact->tags->count())
                                                -
                                                @foreach ($contact->tags as $tag)
                                                    <span
                                                          class="bg-secondary m2-2 rounded px-2 py-1 text-white">{{ $tag->name }}</span>
                                                @endforeach
                                            @else
                                                {{ @$contact->email }}
                                            @endif

                                        </label>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p>No Contacts.</p>
                        @endif

                    </div>
                    <div class="modal-footer">
                        <button type="button"
                                class="btn btn-secondary"
                                data-bs-dismiss="modal">Close</button>

                    </div>
                </div>
            </div>
        </div>
    @endteleport
</div>

@script
    <script>
        $(function() {
            initCompanieselect2();
            initTagsSelect2();
        });

        window['onCompanyContactIdChange'] = function(e) {
            // console.log(e);
            // console.log($("[name=primary_contact]:checked").length);
            let $ids = [];

            $(".project_company_primary_contacts:checked").each(function(i, v) {
                // console.log(v);
                $ids.push(v.value);
            })
            // console.log($ids);
            $wire.set('primaryContacts', $ids);
        }

        function toggleLoader(show = false, timer = 1000) {
            if (show) {
                $("#loading").removeClass('d-none').addClass('d-flex');
            } else {
                if (timer) {
                    setTimeout(function() {
                        $("#loading").removeClass('d-flex').addClass('d-none');
                    }, timer);
                } else {
                    $("#loading").removeClass('d-flex').addClass('d-none');
                }
            }
        }

        function initCompanieselect2() {
            $("#companyId").select2({
                placeholder: "Add company to project",
                allowClear: true
            }).on('change', function(e) {
                if (e.target.value) {
                    toggleLoader(true);
                    $wire.set('companyId', e.target.value);
                }
            });
        }

        function initTagsSelect2() {
            $("#company-tags").select2({
                placeholder: "Search Tags",
                allowClear: true
            }).on('change', function(e) {
                toggleLoader(true);
                // console.log($("#estimators").val());
                $wire.set('tags', $("#company-tags").val());
                // if (e.target.value) {
                // }
            });
        }

        $wire.on('close', (e) => {
            setTimeout(function() {
                // $("#companyId").val(null);

                initCompanieselect2();
                initTagsSelect2();
            }, 100);
            toggleLoader(false, 2000);

        });
    </script>
@endscript
