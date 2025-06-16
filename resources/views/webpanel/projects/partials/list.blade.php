<?php $i = 1; ?>
@foreach ($projects as $k => $project)
    <tr class="deleteBox">
        <td><input type="checkbox" class="form-check-input checkbox-slaves" name="ids[]" value="{{ $project->id }}"></td>
        <td><a href="{{ sysRoute('projects.show', encryptIt($project->id)) }}">{{ $project->pn }} </a></td>
        <td>{{ @$project->facility->name }}</td>
        <td>{{ $project->name }}</td>
        <td> {!! @\App\Models\Project::$statusLabel[$project->status] !!}</td>
        <td class="text-end">
            <a href="{{ sysRoute('project.message', encryptIt($project->id)) }}" class="btn-sm me-1">
                <i class="ti ti-mail"></i>
            </a>
            <a href="{{ sysRoute('project.call', encryptIt($project->id)) }}" class="btn-sm me-1">
                <i class="ti ti-phone"></i>
            </a>
            @if(canEdit())
                <a href="<?php echo sysRoute('projects.live-edit', encryptIt($project->id)); ?>" title="Edit Project" class="btn-sm me-1">
                    <i class="ti ti-edit"></i>
                </a>
            @endif
            @if(canDelete())
                <a title="Delete Project" href="#"
                   class="btn-sm ajaxdelete me-1"
                   data-id="<?php echo $project->id; ?>"
                   data-url="<?php echo sysUrl('projects/delete/' . encryptIt($project->id)); ?>"
                   data-token="<?php echo urlencode(md5($project->id)); ?>">
                    <i class="text-danger ti ti-trash"></i>
                </a>
            @endif
        </td>
    </tr>
    <?php $i++; ?>
@endforeach