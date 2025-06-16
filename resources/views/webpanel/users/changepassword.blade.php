<x-app-layout title="Change Password">

    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-lg-12 mb-4">
                <div class="card mb-4">
                    <h5 class="card-header">Change Password</h5>
                    <div class="card-body">
                        <form method="post" action="{{ url('webpanel/my/password') }}">
                            {{-- <div class="row">
                                <div class="mb-3 col-md-6 form-password-toggle">
                                    <label class="form-label" for="currentpassword">Current Password</label>
                                    <div class="input-group input-group-merge has-validation">
                                        <input class="form-control" type="password" name="currentpassword" id="currentpassword">
                                        <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                                    </div>
                                </div>
                            </div> --}}
                            <div class="row">
                                <div class="mb-3 col-md-6 form-password-toggle">
                                    <label class="form-label" for="password">New Password</label>
                                    <div class="input-group input-group-merge has-validation">
                                        <input class="form-control" type="password" id="password" name="password">
                                        <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                                    </div>
                                </div>

                                <div class="mb-3 col-md-6 form-password-toggle">
                                    <label class="form-label" for="password_confirmation">Confirm New Password</label>
                                    <div class="input-group input-group-merge has-validation">
                                        <input class="form-control" type="password" name="password_confirmation" id="password_confirmation">
                                        <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                                    </div>
                                </div>
                                <div class="col-12 mb-4">
                                    <h6>Password Requirements:</h6>
                                    <ul class="ps-3 mb-0">
                                        <li class="mb-1">Minimum 8 characters long - the more, the better</li>
                                        <li class="mb-1">At least one lowercase character</li>
                                        <li>At least one number, symbol, no space character</li>
                                    </ul>
                                </div>
                                <div>
                                    <button type="submit" class="btn rounded-pill btn-dark">Save changes</button>
                                    <button type="reset" class="btn rounded-pill btn-secondary">Cancel</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

</x-app-layout>
