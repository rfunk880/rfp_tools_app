<x-app-layout title="Sales Report">

    <div class="container-fluid flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-lg-12 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title header-elements">
                            <h5 class="m-0 me-2">Sales Person Report - {{ date("m/d/Y")}}</h5>
                            <div class="card-title-elements ms-auto">
                                <form method="GET" class="d-flex align-items-center gap-2">
                               
                                    <div class=" mb-2">
                                        <label for="date_from">Date From</label>
                                        <input id="date_from" type="text" class="form-control date" name="date_from" value="{{ request('date_from')}}">
                                    </div>
                                    <div class=" mb-2">
                                        <label for="date_to">Date To</label>
                                        <input id="date_to" type="text" class="form-control date" name="date_to" value="{{ request('date_to')}}">
                                    </div>
                                <input type="submit" name="filter" value="Filter" class="btn btn-dark mt-2"/>
                                <a href="{{ sysRoute('reports.sales') . '?pdf=1&'.http_build_query(request()->except('page')) }}" class="btn btn-dark mt-2">Download</a>
                                </form>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table-striped ajaxTable deleteArena table" data-url="<?php echo sysRoute('reports.sales'); ?>">
                                <thead>
                                    <tr>
                                        <th class="sortableHeading" data-orderBy="pn">PN</th>
                                        <th class="sortableHeading" data-orderBy="name">Project</th>
                                        <th class="sortableHeading" data-orderBy="facilities.name">Facility</th>
                                        <th class="" data-orderBy="facilities.name">Sales Persons</th>
                                        <th class="sortableHeading" data-orderBy="final_estimate">Final Price</th>
                                        {{-- <th class="sortableHeading" data-orderBy="total_won">Total Won</th> --}}
                                    </tr>
                                </thead>
                                <tbody class="table-border-bottom-0">
                                    
                                </tbody>
                            </table>
                        </div>
                        <nav id="paginationWrapper" class="text-dark p-3"></nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
