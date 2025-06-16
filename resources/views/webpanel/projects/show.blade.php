<x-app-layout title="Projects">

    <div class="container-fluid flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-lg-9 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title header-elements">
                            <h5 class="fw-bold m-0 me-2">Project {{ $project->name }}</h5>
                            <div class="card-title-elements ms-auto">
                                @if (canEdit())
                                    <a href="{{ sysRoute('projects.live-edit', encryptIt($project->id)) }}"
                                       class="btn btn-primary me-2 mr-2">Edit</a>
                                    @if ($next)
                                        <a href="{{ sysRoute('projects.show', encryptIt($next->id)) }}"
                                           class="btn btn-outline-primary btn-sm">
                                            ← Previous ({{ $next->pn }})
                                        </a>
                                    @endif
                                    @if ($previous)
                                        <a href="{{ sysRoute('projects.show', encryptIt($previous->id)) }}"
                                           class="btn btn-outline-primary btn-sm">
                                            Next ({{ $previous->pn }}) →
                                        </a>
                                    @endif
                                @endif
                                <a href="{{ sysRoute('projects.index') }}"
                                   class="btn btn-secondary btn-sm">Back to the list</a>
                                {!! @\App\Models\Project::$statusLabel[$project->status] !!}
                            </div>
                        </div>

                        <div>
                            <div class="form-group row">
                                <div class="col-md-6">
                                    <label for="pn">PN:</label>
                                    {{ $project->pn }}
                                </div>
                                <div class="col-md-6">
                                    <label for="pn">Project Created Date:</label>
                                    {{ toTimezoneDate($project->created_at, 'm-d-Y H:i', @$project->metadata['timezone']) }}
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-6">
                                    <label for="site_visit">Site Visit:</label>
                                    {{ $project->site_visit ? toTimezoneDate($project->site_visit, 'm-d-Y H:i', @$project->metadata['timezone']) : '' }}
                                </div>

                                <div class="col-md-6">
                                    <label for="bid_due">Bid Due:</label>
                                    {{ $project->bid_due ? toTimezoneDate($project->bid_due, 'm-d-Y H:i', @$project->metadata['timezone']) : '' }}
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-6">
                                    <label for="subcontractor_bid_due">Subcontractor Bid Due:</label>
                                    {{ $project->subcontractor_bid_due ? toTimezoneDate($project->subcontractor_bid_due, 'm-d-Y H:i', @$project->metadata['timezone']) : '' }}
                                </div>

                                <div class="col-md-6">
                                    <label for="bid_document">Bid Document:</label>
                                    <a href="{{ $project->bid_document ? $project->bid_document : '#' }}"
                                       target="_blank">{{ $project->bid_document }}</a>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-6">
                                    <label for="">Salesperson:</label>
                                    {{ $project->salesPersons->pluck('name')->join(', ') }}
                                </div>

                                <div class="col-md-6">
                                    <label for="po_status">PO Status:</label>
                                    {!! @\App\Models\Project::$poStatusLabel[$project->po_status] !!}
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-6">
                                    <label for="est_start_date">Est Start Date:</label>
                                    {{ toAppDate($project->est_start_date) }}
                                </div>

                                <div class="col-md-6">
                                    <label for="est_end_date">Est End Date:</label>
                                    {{ toAppDate($project->est_end_date) }}
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-6">
                                    <label for="final_estimate">Final Estimate:</label>
                                    {{ money($project->final_estimate) }}
                                </div>

                                <div class="col-md-6">
                                    <label for="duration">Duration (days):</label>
                                    {{ (int) $project->duration }}
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-6">
                                    <label for="estimators">Estimators:</label>
                                    {{ $project->estimators->pluck('name')->join(', ') }}
                                </div>
                                <div class="col-md-6">
                                    <label>Probability:</label>
                                    {{ (int) $project->probability }}
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-6">
                                    <label for="public_notes">Public Notes:</label>
                                    {!! $project->public_notes !!}
                                </div>

                                <div class="col-md-6">
                                    <label for="internal_notes">Internal Notes:</label>
                                    {!! $project->internal_notes !!}
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-6">
                                    <label for="estimators">Last Modified At:</label>
                                    {{ $project->lastUpdatedAt() }}
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-header d-flex align-items-center fw-bold">
                        Project Messages
                        @if (canAdd())
                            <a title="Send Mail" href="{{ sysRoute('project.message', encryptIt($project->id)) }}?redirect_to_project=1"
                               class="btn-sm ms-auto">
                                <i class="ti ti-mail"></i>
                            </a>
                        @endif
                    </div>
                    <div class="card-body">
                        <table class="table-striped deleteArena table"
                               data-url="<?php echo sysRoute('messages.index'); ?>">
                            <thead>
                                <tr>
                                    <th style="width:50px;">&nbsp;</th>
                                    <th class="sortableHeading" data-orderBy="subject"
                                        style="width:200px;">Subject</th>
                                    <th style="width:400px;">Recepients</th>
                                    <th style="width:100px;">Sent At</th>
                                </tr>
                            </thead>
                            <tbody class="table-border-bottom-0">
                                <?php $i = 1; ?>
                                @foreach ($project->messages as $k => $message)
                                    <tr class="deleteBox">
                                        <td>{{ $k + 1 }}</td>
                                        <td>
                                            <a href="{{ sysRoute('messages.show', encryptIt($message->id)) }}">
                                                {!! $message->media->count() ? '<i class="fa fa-file"></i>' : '' !!}
                                                {{ $message->subject }} {{-- <br /> {{ $message->phone }} --}} </a>
                                        </td>
                                        <td>
                                            {!! $message->recepients->map(function ($item) {
                                                    return '<span class="p-2 mb-2 me-2">' . $item->name . '&lt;' . $item->email . '&gt;</span><br/>';
                                                })->join(' ') !!}
                                        </td>
                                        <td>{{ $message->created_at->format('d M, Y g:i A') }}</td>

                                    </tr>
                                    <?php $i++; ?>
                                @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
                <div class="card mt-4">
                    <div class="card-header d-flex align-items-center fw-bold">
                        Project Call Logs
                        @if (canAdd())
                            <a title="Call Log"
                               href="{{ sysRoute('project.call', encryptIt($project->id)) }}?redirect_to_project=1"
                               class="btn-sm ms-auto"> <i class="ti ti-phone"></i>
                            </a>
                        @endif
                    </div>
                    <div class="card-body">
                        <table class="table-striped deleteArena table" data-url="<?php echo sysRoute('calllogs.index'); ?>">
                            <thead>
                                <tr>
                                    <th style="width:50px;">&nbsp;</th>
                                    <th class="sortableHeading" data-orderBy="subject" style="width:200px;">Subject</th>
                                    <th style="width:400px;">Recepient</th>
                                    <th style="width:100px;">Called At</th>
                                </tr>
                            </thead>
                            <tbody class="table-border-bottom-0">
                                <?php $i = 1; ?>
                                @foreach ($project->calllogs as $k => $message)
                                    <tr class="deleteBox">
                                        <td>{{ $k + 1 }}</td>
                                        <td>
                                            <a href="{{ sysRoute('calllogs.show', encryptIt($message->id)) }}">
                                                {{ $message->subject }} {{-- <br /> {{ $message->phone }} --}} </a>
                                        </td>
                                        <td>
                                            {!! $message->recepients->map(function ($item) {
                                                    return '<span class="p-2 mb-2 me-2">' . $item->name . ' (' . $item->phone . ') </span><br/>';
                                                })->join(' ') !!}
                                        </td>
                                        <td>{{ $message->created_at->format('d M, Y g:i A') }}</td>
                                    </tr>
                                    <?php $i++; ?>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-lg-3">
                <div class="card mb-4">
                    <div class="card-header d-flex align-items-center fw-bold">
                        Facility
                    </div>
                    <div class="card-body">
                        @if ($project->facility)
                            <p class="mb-0">
                                <strong>Name: </strong> {{ @$project->facility->name }}<br />
                                <strong>Owner: </strong> {{ @$project->facility->owner }}<br />
                                <strong>Location: </strong> {{ @$project->facility->location }}
                            </p>
                        @endif
                        </>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header d-flex align-items-center fw-bold">
                        Clients
                    </div>
                    <div class="card-body">
                        @if ($project->clients->count())
                            @foreach ($project->clients as $client)
                                <a href="{{ sysRoute('companies.edit', encryptIt($client->id)) }}">
                                    {{ $client->name }}</a>
                                <br />
                                @if (@$client->pivot->primary_contacts && is_array(@$client->pivot->primary_contacts))
                                    @foreach (@$client->pivot->primary_contacts as $contact)
                                        <span>{{ @$contact['name'] }}, {{ @$contact['email'] }},
                                            {{ @$contact['phone'] }}</span><br />
                                    @endforeach
                                @endif
                                <br /><br />
                            @endforeach
                        @else
                            <x-alert type="info" message="No companies added." />
                        @endif
                    </div>
                </div>
                <div class="card">
                    <div class="card-header d-flex align-items-center fw-bold">
                        Vendors
                    </div>
                    <div class="card-body">
                        @if ($project->companies->count())
                            @foreach ($project->companies as $company)
                                <a
                                   href="{{ sysRoute('companies.show', encryptIt($company->id)) }}">{{ $company->name }}</a>
                                <br />
                                @if (@$company->pivot->primary_contacts && is_array(@$company->pivot->primary_contacts))
                                    @foreach (@$company->pivot->primary_contacts as $contact)
                                        <span>{{ @$contact['name'] }}</span>
                                        @if (@$contact['tags'] && is_array($contact['tags']))
                                            -
                                            @foreach ($contact['tags'] as $tag)
                                                <span
                                                      class="bg-secondary m2-2 rounded px-2 py-1 text-white">{{ $tag['name'] }}</span>
                                            @endforeach
                                        @else
                                            ,{{ @$contact['email'] }}, {{ @$contact['phone'] }}
                                        @endif
                                        <br />
                                    @endforeach
                                @endif
                                <br /><br />
                            @endforeach
                        @else
                            <x-alert type="info" message="No companies added." />
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
