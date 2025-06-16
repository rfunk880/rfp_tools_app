<x-app-layout title="Add Contact">
    <div class="container-fluid flex-grow-1">
        <div class="row">
            <div class="col-lg-12">
                <div class="card mb-4">
                    <h5 class="card-header">Add New Contact</h5>
                    <div class="card-body">
                        <form class="ajaxForm" method="post" action="<?php echo sysRoute('companies.store'); ?>" data-result-container="#notificationArea" role="form">
                          
                            <input type="hidden" name="project_id" value="{{ request('project_id')}}"/>
                            @include('webpanel.companies.partials.form')
                            @include('webpanel.companies.partials.contact-form')

                            <br/>
                            <button type="submit" class="btn rounded-pill btn-dark">Save</button>
                            <a href="{{ sysRoute('companies.index') }}"  class="btn rounded-pill btn-secondary">Cancel</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

