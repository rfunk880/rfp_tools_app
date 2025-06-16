<x-app-layout title="Bid Reports">
    <div class="container-fluid flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-lg-12 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title header-elements">
                            <h5 class="m-0 me-2">BID Report - {{ date("m/d/Y")}}</h5>
                            <div class="card-title-elements ms-auto">
                                <form method="GET">
                                    {{-- <input type="text" class="form-control" name="keyword" value="{{ request('keyword') }}"  placeholder="Search" /> --}}
                                </form>
                                <a href="{{ sysRoute('reports.bid').'?pdf=1' }}" class="btn btn-sm btn-dark">Download</a>
                               </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table-striped deleteArena ajaxTable table" data-url="<?php echo sysRoute('reports.bid'); ?>">
                                <thead>
                                    <tr>
                                        <th class="sortableHeading" data-orderBy="status">Status</th>
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
                                  
                                </tbody>
                            </table>
                        </div>
                        <nav id="paginationWrapper" class="text-dark p-3">
                        
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>