<x-app-layout title="Facility Report">

    <div class="container-fluid flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-lg-12 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title header-elements">
                            <h5 class="m-0 me-2">Facility Report - {{ date("m/d/Y")}}</h5>
                            <div class="card-title-elements ms-auto">
                                <form method="GET" class="d-flex align-items-center gap-2">
                                <div class="mb-2">
                                  <label for="is_key_account">Key Accounts</label>   
                                    <div class="form-check form-switch mb-2">
                                    <input class="form-check-input submitOnChange" type="checkbox" id="is_key_account" name="is_key_account" {!! isChecked('on', request('is_key_account')) !!} />
                                    {{-- <label class="form-check-label" for="is_key_account">Check/Uncheck&nbsp;</label> --}}
                                   </div>
                                    </div>
                                    
                                    <div class="mb-2" style="width:200px;">
                                        <label for="status">Status</label>
                                        <select name="status[]" data-placeholder='' multiple class="select2 form-select form-select-sm submitOnChange">
                                           <option value="">All</option>
                                           {!! arrayOptions(@\App\Models\Project::$statusLabel, request('status', 'a')) !!}
                                        </select>
                                    </div>
                                    
                                    <div class="mb-2">
                                        <label for="date_from">Date From</label>
                                        <input id="date_from" type="text" class="form-control date" name="date_from" value="{{ request('date_from')}}">
                                    </div>
                                    <div class="mb-2">
                                        <label for="date_to">Date To</label>
                                        <input id="date_to" type="text" class="form-control date" name="date_to" value="{{ request('date_to')}}">
                                    </div>
                                <input type="submit" name="filter" value="Filter" class="btn btn-dark mt-2"/>
                                <a href="{{ sysRoute('reports.facility') . '?pdf=1' }}" class="btn btn-dark mt-2">Download</a>
                                </form>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table-striped ajaxTable deleteArena table" data-url="<?php echo sysRoute('reports.facility'); ?>">
                                <thead>
                                    <tr>
                                        <th class="sortableHeading" data-orderBy="facility_id">Facility</th>
                                        <th class="sortableHeading" data-orderBy="total_submitted">Total Submitted</th>
                                        <th class="sortableHeading" data-orderBy="total_won">Total Won</th>
                                        <th>Win Rate</th>
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
