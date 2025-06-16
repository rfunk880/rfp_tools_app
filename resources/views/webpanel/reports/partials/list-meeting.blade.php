<?php $i = 1; ?>
@foreach ($projects as $k => $project)
    <tr class="deleteBox">
        <td> {{ $project->pn}}</td>
        <td>{{ $project->bid_due ? toTimezoneDate($project->bid_due, 'm-d-Y H:i', @$project->metadata['timezone']) : '' }}</td>
        <td>{{ $project->site_visit ? toTimezoneDate($project->site_visit, 'm-d-Y H:i', @$project->metadata['timezone']) : '' }}</td>
        <td>{{ @$project->facility->name }}, {{ @$project->facility->location }}</td>
        <td>
            <a href="{{ sysRoute('projects.show', encryptIt($project->id)) }}">
                {{ $project->name }}
            </a>
        </td>
        <td>
            @foreach ($project->estimators as $estimator)
                {{ $estimator->name }},<br />
            @endforeach
        </td>
        <td>
            @foreach ($project->salesPersons as $estimator)
                {{ $estimator->name }},<br />
            @endforeach
        </td>
        <td>
            @foreach ($project->clients as $client)
                {{ $client->name }},<br />
            @endforeach
        </td>
        <td>{!! @$project->facility->is_key_account
            ? '<i class="fa fa-check text-success"></i>'
            : '<i class="fa fa-multiply text-danger"></i>' !!}</td>
        <td>{{ $project->est_start_date }} - {{ $project->est_end_date }}</td>
        <td>${{ $project->final_estimate }}</td>
    </tr>
    <?php $i++; ?>
@endforeach
