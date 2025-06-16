<?php $i = 1; ?>
@foreach ($projects as $k => $project)
    <tr class="deleteBox">
        <td>{{ $project->pn }}</td>
        <td>{{ $project->bid_due ? toTimezoneDate($project->bid_due, 'm-d-Y H:i', @$project->metadata['timezone']) : '' }}</td>
        <td>{{ $project->site_visit ? toTimezoneDate($project->site_visit, 'm-d-Y H:i', @$project->metadata['timezone']) : '' }}</td>
        <td>{{ @$project->facility->name }}, {{ @$project->facility->location }}</td>
        <td>{{ $project->name }}</td>
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

    </tr>
    <?php $i++; ?>
@endforeach