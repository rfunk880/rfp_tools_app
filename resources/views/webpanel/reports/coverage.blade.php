<x-app-layout title="Coverage Report">

    <div class="container-fluid flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-lg-12 mb-4">
                <div class="card">
                    <div class="card-body">
                      <div class="card-title header-elements">
                            <h5 class="m-0 me-2 fw-bold">Coverage Report - {{ date("m/d/Y")}}</h5>
                        </div>
                        
                          <form method="GET">
                             <div class="form-group row">
                                <div class="col-md-8">
                                    <label for="pn">Project Number:</label>
                                    <input id="pn" type="text" class="form-control" name="pn" value="{{ request('pn') }}">
                                </div>
                                
                                <div class="col-md-4 mt-4">
                                	<input type="submit" class="btn btn-dark btn-round" value="Search">
                                </div>
                            </div>
                        </form>
                            	
                        @if ($project)
                            <span>
                                Project# {{ $project->pn }}, {{ $project->facility->name }}, {{ $project->name }}
                            </span>

                            @if (count($tags))
                                @foreach ($tags as $tag => $contacts)
                                    <h5 class="mt-3 fw-bold">{{ $tag }}</h4>
                                    <ul>
                                        @foreach($contacts as $contact)
                                        <li>
                                        {{ @$contact->company->name }},{{ @$contact->company->location }},
                                             {{ $contact->name }}, {{ $contact->phone }}, {{ $contact->cell }}
                                        </li>
                                        @endforeach
                                    </ul>
                                @endforeach
                            @else
                                <p class="p-2">No tags found on the vendor contacts.</p>
                            @endif
                        @endif
                        <nav id="paginationWrapper" class="text-dark p-3"></nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
