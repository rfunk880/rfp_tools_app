@foreach ($projects as $k => $project)
    <tr class="deleteBox">
        <td><a href="{{ sysRoute('projects.show', encryptIt($project->id)) }}">{{ $project->pn }} </a></td>
        <td>{{ $project->name }}</td>
        <td>{{ @$project->facility->name }}, {{ @$project->facility->location }}</td>
        <td>
            {{ $project->salesPersons->map(fn($item) => $item->name)->join(', ') }}
        </td>
        <td>{{ money($project->final_estimate) }}</td>
        
        
        </td>
    </tr>
@endforeach
<tr>
    <td colspan="" class="text-end">
        <strong>Total:</strong>
    </td>
    <td>
        <strong>{{ money($total) }}</strong>
    </td>
</tr>
