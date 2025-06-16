
<x-auth-layout title="Reset Password">

<div class="container-xxl">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner py-4">
                <div class="card">
                    <div class="card-body">
                        <div class="justify-content-center mb-4 mt-2">
                            <img src="{{ asset('img/LogoEmail.png') }}" class="img-fluid" >
                        </div>
                        <h4 class="mb-1 pt-2">Reset Password 🔒</h4>
                        <p class="mb-4">Enter your new and confirm password to reset existing password.</p>

						<x-validation-errors class="mb-4" />

                        <form method="POST" action="{{ route('password.update') }}">
                            @csrf
							<input type="hidden" name="token" value="{{ $request->route('token') }}">
							
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="{{$request->email}}" readonly />
                            </div>

                            <div class="mb-3 form-password-toggle">
                                <label class="form-label" for="password">New Password</label>
                                <div class="input-group input-group-merge">
                                    <input type="password" id="password" class="form-control" name="password" aria-describedby="password"/>
                                    <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                                </div>
                            </div>

                            <div class="mb-3 form-password-toggle">
                                <label class="form-label" for="confirm-password">Confirm Password</label>
                                <div class="input-group input-group-merge">
                                    <input type="password" id="password_confirmation" class="form-control" name="password_confirmation" aria-describedby="password" />
                                    <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                                </div>
                            </div>

                            <button class="btn rounded-pill btn-dark w-100 mb-3">Set New Password</button>
                            <div class="text-center">
                                <a href="{{ route('login') }}">
                                    <i class="ti ti-chevron-left scaleX-n1-rtl"></i>
                                    Back to login
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-auth-layout>