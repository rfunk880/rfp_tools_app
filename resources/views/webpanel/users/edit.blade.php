
    <x-app-layout title="Update User">

    <div class="container-fluid flex-grow-1">
        <div class="row">
            <div class="col-lg-12">
                <div class="card mb-4">
                    <h5 class="card-header">Update User</h5>
                    <div class="card-body">
                        <form class="ajaxForm" method="post" action="<?php echo sysRoute('users.update', encryptIt($user->id)); ?>" data-result-container="#notificationArea" role="form">
                            <input type="hidden" name="_method" value="put">
                            <input type="hidden" name="user_type_id" id="user_type_id" value="10">
                        
                            @include('webpanel.users.partials.form')

                            <br/>
                            <button type="submit" class="btn rounded-pill btn-dark">Update user</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-slot:scripts>

    </x-slot:scripts>

</x-app-layout>

