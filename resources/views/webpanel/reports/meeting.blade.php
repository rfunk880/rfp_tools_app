<x-app-layout title="Meeting Reports">
    <div class="container-fluid flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-lg-12 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title header-elements">
                            <h5 class="m-0 me-2">MEETING Report - {{ date("m/d/Y")}}</h5>
                            <div class="card-title-elements ms-auto">
                                <form method="GET">
                                    <div class=" mb-2">
                                        <label for="date_from">Status</label>
                                        <select name="status[]" data-placeholder='' multiple class="form-select form-select-sm select2 submitOnChange me-2 ms-2" style="width:200px;">
                                           {!! arrayOptions(@\App\Models\Project::$statusLabel, request('status', 'a')) !!}
                                        </select>
                                    </div>
                               
                                </form>
                                {{-- <a href="{{ sysRoute('reports.meetings').'?pdf=1' }}" class="btn btn-sm btn-dark">Download</a> --}}
                               </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table-striped deleteArena ajaxTable table" data-url="<?php echo sysRoute('reports.meetings'); ?>">
                                <thead>
                                    <tr>
                                        <th class="sortableHeading" data-orderBy="pn">Project NO</th>
                                        <th class="sortableHeading" data-orderBy="bid_due">Bid Due</th>
                                        <th class="sortableHeading" data-orderBy="site_visit">Site Visit</th>
                                        <th class="sortableHeading" data-orderBy="facility_id">Facility</th>
                                        <th class="sortableHeading" data-orderBy="name">Project Name</th>
                                        <th>Estimators</th>
                                        <th>Sales Persons</th>
                                        <th>Client(s)</th>                                        
                                        <th class="sortableHeading" data-orderBy="is_key_account">Key</th>
                                       <th>Start/Finish</th>
                                        <th>Final Estimate</th>
                                    </tr>
                                </thead>
                                <tbody class="table-border-bottom-0">
                                    {{-- @include('webpanel.reports.partials.list-meeting') --}}
                                </tbody>
                            </table>
                        </div>
                        <nav id="paginationWrapper" class="text-dark p-3">
                            {{-- @include('components.pagination', ['data' => $projects]) --}}
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>