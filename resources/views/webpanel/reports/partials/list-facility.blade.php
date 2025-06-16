@foreach ($projects as $k => $project)
    <tr class="deleteBox">
        <td>{{ @$project->facility->name }}, {{ @$project->facility->location }}</td>
        <td>{{ @money($project->total_submitted) }}</td>
        <td>{{ money($project->total_won) }}</td>
        <td>{{ $project->total_submitted > 0 ? number_format(($project->total_won * 100) / $project->total_submitted, 2) : 'N/A' }}%
        </td>
    </tr>
@endforeach
