<div class="container-fluid flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-md-8">
            @include('webpanel.projects.components.edit.partials.form')
        </div>
        <div class="col-md-4">
            <div class="mb-4">
                @livewire('projects.facility', [
                    'facilityId' => $project->facility_id,
                ])
            </div>
            <div class="mb-4">
                @livewire('projects.clients', [
                    'project' => $project,
                ])
            </div>
            <div class="mb-4">
                @livewire('projects.companies', [
                    'project' => $project,
                ])
            </div>
        </div>
    </div>
</div>