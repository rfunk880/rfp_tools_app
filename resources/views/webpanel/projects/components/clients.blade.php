<div class="card">
    <div class="card-header d-flex align-items-center fw-bold">
        Prime / Owner
        <a class="ms-auto" title="Add New" href="{{ sysRoute('companies.create')}}?context=clients&project_id={{ encryptIt($project->id)}}">
            <span class="tf-icon ti ti-plus ti-xs me-1"></span>
        </a>
    </div>
    <div class="card-body">
        {{-- @dd($this->availableClientOptions) --}}
        <div wire:ignore.self>
            <select id="clientId"
                    class="form-select"
                    wire:model.live="clientId">
                <option value=""></option>
                {!! OptionsView($this->availableClientOptions, 'id', function ($item) {
                    return $item->name /*  . ', ' . $item->owner */;
                }) !!}
            </select>
        </div>
        @if ($project->clients->count())
            <ul class="list-group mt-4">
                @foreach ($project->clients as $client)
                    <li class="list-group-item w-full">
                        <div class="d-flex">
                            <a href="{{ sysRoute('companies.show', encryptIt($client->id)) }}"> {{ $client->name }}</a>
                            <a class="ms-auto"
                               href="#"
                               wire:click.prevent="confirmRemove({{ $client->id }})">Remove</a>
                        </div>
                        <div class="mt-1 w-full">
                            <label class="cursor"
                                   role="button"
                                   title="Select Primary Contact"
                                   wire:click="toggleActiveCompany({{ $client->id }})"
                                   data-bs-toggle="modal"
                                   data-bs-target="#clientPrimaryContactModal">

                                @if (@$client->pivot->primary_contacts && is_array(@$client->pivot->primary_contacts))
                                    @foreach (@$client->pivot->primary_contacts as $contact)
                                        <span>{{ @$contact['name'] }} {{ @$contact['email'] }}</span><br />
                                    @endforeach
                                @else
                                    <span>{{ $client->contacts->count() ? 'Select Primary Contact' : 'No Contacts' }}</span>
                                @endif

                            </label>

                        </div>
                    </li>
                @endforeach
            </ul>
        @else
            <x-alert type="info"
                     message="No clients added." />
        @endif
    </div>
    @teleport('#stack-modals')
        <div id="clientPrimaryContactModal"
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
                        @if ($this->selectedCompany && $this->selectedCompany->contacts->count())
                            <ul class="list-group"
                                x-data="{}">
                                @foreach ($this->selectedCompany->contacts as $contact)
                                    <li class="list-group-item">
                                        <label>
                                            <input id="primary_contact_checkbox_{{ $contact->id }}"
                                                   type="checkbox"
                                                   x-on:change="onContactIdChange"
                                                   class="primary_contacts"
                                                   name="primary_contact"
                                                   {!! isMultiChecked($contact->id, $primaryContacts) !!}
                                                   value="{{ $contact->id }}">
                                            <span class="ms-2">{{ $contact->name }}, {{ $contact->email }}</span>

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
            initClientSelect2();
        });

        window['onContactIdChange'] = function(e) {
            // console.log(e);
            // console.log($("[name=primary_contact]:checked").length);
            let $ids = [];

            $(".primary_contacts:checked").each(function(i, v) {
                // console.log(v);
                $ids.push(v.value);
            })
            // console.log($ids);
            $wire.set('primaryContacts', $ids);
        }

        function initClientSelect2() {
            $("#clientId").select2({
                placeholder: "Add client to project",
                allowClear: true
            }).on('change', function(e) {
                if (e.target.value) {
                    $wire.set('clientId', e.target.value);
                }
            });
        }
        $wire.on('close', (e) => {
            // console.log($wire.clientId.toString());
            // const $selected = $wire.clientId;
            setTimeout(() => {
                // $("#clientId").val(null);
                initClientSelect2();
            }, 300);
        });
    </script>
@endscript
