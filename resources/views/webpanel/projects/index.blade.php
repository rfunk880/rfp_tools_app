<x-app-layout title="Projects">

    <div class="container-fluid flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-lg-12 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title header-elements">
                            <h5 class="m-0 me-2">Project Management</h5>
                            <div class="card-title-elements ms-auto">
                                <form id="project-filter-form" method="GET" class="d-flex w-full">
                                    <input type="hidden" name="per_page" value="{{ request('per_page', 10) }}" />
                                    <input type="text" class="form-control" name="keyword" value="{{ request('keyword') }}"
                                           placeholder="Search" />
                                    <select name="status" class="form-select submitOnChange me-2 ms-2" style="width:150px;">
                                        <option value="">Active</option>
                                        <option value="all"
                                                {!! isSelected('all', request('status')) !!}>View All</option>
                                        {!! arrayOptions(@\App\Models\Project::$statusLabel, request('status', 'a')) !!}
                                    </select>
                                </form>
 							@if (canAdd())
                                <a href="#" data-bs-toggle="modal"
                                   data-bs-target="#addProjectModal"
                                   class="btn btn-dark"><span class="tf-icon ti ti-plus ti-xs me-1"></span>New Project</a>
 							@endif
                                <a href="{{ url()->current() }}" class="btn btn-secondary">Reset</a>

                                @if (canAdd())
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#bulkProjectModal" class="btn btn-dark">Bulk Upload</a>
                                @endif
                                @if (isManagement())
                                    <a href="{{ sysRoute('projects.export', request()->except('page')) }}" data-loading='Exporting...'
                                       class="btn btn-dark get_action_button">Export</a>
                                @endif
                            </div>
                        </div>
                        <form method="POST" action="{{ sysRoute('projects.bulk-action') }}">
                            <div class="table-responsive">
                                <table class="table-striped deleteArena ajaxTable table" data-url="<?php echo sysRoute('projects.index'); ?>">
                                    <thead>
                                        <tr>
                                            <th><input type="checkbox" class="form-check-input checkbox-master"></th>
                                            <th class="sortableHeading" data-orderBy="pn">Project Number</th>
                                            <th>Facility Name</th>
                                            <th class="sortableHeading" data-orderBy="name">Project</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-border-bottom-0">
                                    </tbody>
                                </table>
                            </div>
                            @if (canBulkDelete())
                                @if (\App\Models\Project::count('id'))
                                <div class="mt-4">
                                    <button type="submit" class="btn btn-danger btn-sm" name="action" value="delete">Delete Selected</button>
                                    <button type="submit" class="btn btn-danger btn-sm confirm" data-message="This will remove all companies and their assotiated contacts. Are you sure you want to proceed?"
                                            name="action" value="deleteall">Delete All</button>
                                    <button type="submit" class="btn btn-primary btn-sm confirm" data-message="Are you sure you want to edit selected items?"
                                            name="action" value="edit">Bulk Edit</button>
                                </div>
                                @endif
                            @endif
                        </form>
                        <nav id="paginationWrapper" class="text-dark p-3"></nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-slot:modals>
        <div id="addProjectModal"
             class="modal fade"
             tabindex="-1"
             role="dialog"
             aria-labelledby="modalTitleId"
             aria-hidden="true">
            <div class="modal-dialog"
                 role="document">
                <div class="modal-content">
                    <form method="post"
                          class="ajaxForm"
                          data-notification-area="#project-create-notification"
                          action="{{ sysRoute('projects.store') }}">
                        @csrf
                        <div class="modal-header">
                            <h5 id="modalTitleId" class="modal-title"> Add New Project</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body" x-data="{ offset: getTimeZone() }">
                        
                            <input type="hidden" name="timezone_offset" x-model="offset" />
                            <div id="project-create-notification"></div>
                            <label>Project Name:</label>
                            <input type="text" class="form-control" name="name">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-dark">Next</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div id="bulkProjectModal" class="modal fade"
             tabindex="-1"
             role="dialog"
             aria-labelledby="modalTitleId"
             aria-hidden="true">
            <div class="modal-dialog"
                 role="document">
                <div class="modal-content">
                    <form method="post" action="{{ sysRoute('projects.import') }}" enctype="multipart/form-data" data-notification-area="#project-bulk-notification">
                        @csrf
                        <div class="modal-header">
                            <h5 id="modalTitleId" class="modal-title"> Upload Bulk Project CSV</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body" x-data="{ offset: getTimeZone() }">
                            {{-- <h4 class="card-title">Information</h4> --}}
                            <input type="hidden" name="timezone_offset" x-model="offset" />
                            <div id="project-bulk-notification"></div>
                            <label>CSV File:</label>
                            <input type="file" class="form-control" name="file">
                            <p class="small mt-3">Note: Select CSV format file to upload. Please note that all the
                                defined fields should as exact on place on the file. <a
                                   href="{{ asset('project-sample.csv') }}">Click here</a> to download the sample file
                                for your overview.</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-dark">Upload</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </x-slot:modals>

</x-app-layout>
