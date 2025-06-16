<x-app-layout title="500">
    <div class="row h-100 align-items-center">
        <div class="col-lg-6 col-sm-12">
            <div class="form-input-content  error-page">
				<h1 class="error-text text-danger">500</h1>
				<h4> Internal Server Error</h4>
				<p>You do not have permission to view this resource.</p>
                <a class="btn btn-primary" href="{{ url('/') }}">Back to Home</a>
            </div>
        </div>
        <div class="col-lg-6 col-sm-12">
			
		</div>
    </div>

</x-app-layout>
