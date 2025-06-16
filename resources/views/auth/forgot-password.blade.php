<x-auth-layout title="Reset Password">

    <div class="container-xxl">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner py-4">
                <div class="card">
                    <div class="card-body">
                        <div class="justify-content-center mb-4 mt-2">
                            <img src="{{ asset('img/LogoEmail.png') }}" class="img-fluid" >
                        </div>

                        <h4 class="mb-1 pt-2">Forgot Password? 🔒</h4>
                        <p class="mb-4">Enter your email and we'll send you instructions to reset your password</p>
                        <form class="mb-3" method="POST" action="{{ route('password.email') }}">
                            @csrf
                            @include('includes.notifications')
                            @if (session('status'))
                                <p class="text-success text-bold">{{ session('status') }}</p>
                            @endif

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" />
                            </div>
                            <button class="btn rounded-pill btn-dark w-100" type="submit">Send Reset Link</button>
                        </form>
                        <div class="text-center">
                            <a href="{{ route('login') }}" class="d-flex align-items-center justify-content-center">
                                <i class="ti ti-chevron-left scaleX-n1-rtl"></i>
                                Back to login
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


</x-auth-layout>
