<x-app-layout title="Messaging ">

    <div class="container-fluid flex-grow-1 container-p-y">
        <div class="card">
            <div class="card-body">
                <div class="card-title header-elements">
                    <h5 class="m-0 me-2">Save Call log for project {{ $project->name }}</h5>
                </div>

                <form class="ajaxForm" method="post" action="<?php echo sysRoute('calllogs.store'); ?>" data-result-container="#notificationArea" role="form">
                    <input type="hidden" name="project_id" value="{{ @$project->id }}" />
                    <input type="hidden" name="redirect_to_project" value="{{ @$redirect_to_project }}" />
                    @include('webpanel.calllogs.partials.form')

                    <br />
                    <button type="submit" class="btn rounded-pill btn-dark">Save</button>
                    <a href="{{ sysRoute('calllogs.index') }}" class="btn rounded-pill btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>

</x-app-layout>
