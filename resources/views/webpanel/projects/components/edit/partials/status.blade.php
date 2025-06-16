<div class="ms-auto d-flex align-items-center gap-2" x-data="{ editing: false }">
    @if (canEdit())
    <div wire:ignore>
        {{-- <select class="form-select-sm select2 redirectOnChange" data-placeholder="Move to" style="width:150px;" name="goto" data-url="{{ sysRoute('projects.live-edit', '_id') }}">
            <option value="">Go to</option>
            @foreach (\App\Models\Project::forMe()->select('id', 'pn')->get() as $p)
                <option value="{{ encryptIt($p->id) }}">{{ $p->pn }}</option>
            @endforeach
        </select> --}}
        @if ($next)
        <a href="{{ sysRoute('projects.live-edit', encryptIt($next->id)) }}"
           class="btn btn-outline-primary btn-sm">
            ← Previous ({{ $next->pn }})
        </a>
    @endif
    @if ($previous)
        <a href="{{ sysRoute('projects.live-edit', encryptIt($previous->id)) }}"
           class="btn btn-outline-primary btn-sm">
            Next ({{ $previous->pn }}) →
        </a>
    
    @endif
    </div>
    @endif
    <template x-if="editing">
        <select class="form-select" wire:model.live="project.status" x-on:change="editing=false;">
            {!! arrayOptions(@\App\Models\Project::$statusLabel, $project->status) !!}
        </select>
    </template>
    <template x-if="!editing">
        <a x-on:click.prevent="editing=true;" title="Click To Update Status">
            {!! @\App\Models\Project::$statusLabel[$project->status] !!}
        </a>
    </template>
</div>
