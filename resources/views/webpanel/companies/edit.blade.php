<x-app-layout title="Update Contact">
    <div class="container-fluid flex-grow-1">
        <div class="row">
            <div class="col-lg-12">
                <div class="card mb-4">
                    <h5 class="card-header">Update Contact</h5>
                    <div class="card-body">
                        
                    <form class="ajaxForm" method="post" action="<?php echo sysRoute('companies.update', encryptIt($company->id)); ?>" role="form" data-result-container="#notificationArea" data-notification-animation="1">
                        @method('put')

                        @include('webpanel.companies.partials.form', ['model' => $company])
                        @include('webpanel.companies.partials.contact-form')

                        <div>
                            <div class="mt-4">
                                <button type="submit" class="btn btn-dark mr-2">Save</button>
                                <a href="{{ sysRoute('companies.index') }}" class="btn btn-secondary">Cancel</a>
                            </div>
                        </div>
                    </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <x-slot:styles>
        {!! jsVar($jsData) !!}
    </x-slot:styles>
</x-app-layout>



        
