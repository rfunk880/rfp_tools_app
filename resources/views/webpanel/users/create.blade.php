<x-app-layout title="Add User">
    <div class="container-fluid flex-grow-1">
        <div class="row">
            <div class="col-lg-12">
                <div class="card mb-4">
                    <h5 class="card-header">Add New User</h5>
                    <div class="card-body">
                        <form class="ajaxForm" method="post" action="<?php echo sysRoute('users.store'); ?>" data-result-container="#notificationArea" role="form">
                            <input type="hidden" name="user_type_id" value="10" />

                            @include('webpanel.users.partials.form')

                            <br/>
                            <button type="submit" class="btn rounded-pill btn-dark">Add User</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>