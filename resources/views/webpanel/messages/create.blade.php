<x-app-layout title="Messaging ">

    <div class="container-fluid flex-grow-1 container-p-y">
        <div class="card">
            <div class="card-body">
                <div class="card-title header-elements">
                    <h5 class="m-0 me-2">Send Message to project {{ $project->name }}</h5>
                </div>

                <form class="ajaxForm" method="post" action="<?php echo sysRoute('messages.store'); ?>" data-result-container="#notificationArea" role="form">
                    <input type="hidden" name="project_id" value="{{ @$project->id }}" />
                    <input type="hidden" name="redirect_to_project" value="{{ @$redirect_to_project }}" />
                    @include('webpanel.messages.partials.form')

                    <br />
                    <button type="submit" class="btn rounded-pill btn-dark">Send</button>
                    <a href="{{ sysRoute('messages.index') }}" class="btn rounded-pill btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>

</x-app-layout>
