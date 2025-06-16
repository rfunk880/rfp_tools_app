<x-app-layout title="404">

<div class="container-fluid flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-lg-12 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <div class="form-input-content  error-page">
                                <h1 class="error-text text-danger">404</h1>
                                <h4>The page you were looking for is not found!</h4>
                                <p>You may have mistyped the address or the page may have moved.</p>
                                <a class="btn btn-primary" href="{{ url('/') }}">Back to Home</a>
                            </div>
                            <div class="col-lg-6 col-sm-12">
                            
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>

</x-app-layout>