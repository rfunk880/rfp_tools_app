<x-app-layout title="Companies">

    <div class="container-fluid flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-lg-12 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title header-elements">
                            <h5 class="m-0 me-2">Contact Management</h5>
                            <div class="card-title-elements ms-auto">
                                <form id="contact-filter-form"
                                      method="GET"
                                      class="d-flex align-items-center">
                                    <input type="text"
                                           class="form-control me-2"
                                           name="keyword"
                                           value="{{ request('keyword') }}"
                                           placeholder="Search" />
                                    <div class="tags-select2-container">
                                        <select class="select2 submitOnChange"
                                                name="tags[]"
                                                data-placeholder='All Tags'
                                                multiple>
                                            {!! OptionsView(\App\Models\Tag::all(), 'id', 'name', request('tags', [])) !!}
                                        </select>
                                    </div>
                                    <div class="d-flex me-2 ms-2">
                                        @foreach (\App\Models\Company::$TYPES as $type)
                                            <div class="form-check form-check-inline">
                                                <input id="company_type_{{ $type }}"
                                                       name="company_type[]"
                                                       class="form-check-input submitOnChange"
                                                       type="checkbox"
                                                       {!! isMultiChecked($type, request('company_type', [])) !!}
                                                       value="{{ $type }}" />
                                                <label class="form-check-label"
                                                       for="company_type_{{ $type }}">{{ $type }}</label>
                                            </div>
                                        @endforeach
                                        <input type="hidden" name="per_page" value="{{ request('per_page', 10) }}" />
                                    </div>
                                </form>
                                @if (canAdd())
                                    <a href="{{ sysRoute('companies.create') }}" class="btn btn-dark btn-sm ms-2"><i class="fa fa-plus"></i> Add New</a>
                                @endif
                                <a href="{{ url()->current() }}" class="btn btn-sm btn-secondary">Reset</a>

                                @if (canAdd())
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#bulkContactsModal" class="btn btn-sm btn-dark">Bulk Upload</a>
                                @endif
                                @if (isManagement())
                                <a href="{{ sysRoute('companies.export') }}" data-loading='Exporting...'  class="btn btn-dark btn-sm get_action_button">Export</a>
                            @endif
                            </div>
                        </div>
                        <form method="POST" action="{{ sysRoute('companies.bulk-action') }}">
                            <div class="table-responsive">
                                <table class="table-striped deleteArena ajaxTable table"
                                       data-url="<?php echo sysRoute('companies.index'); ?>">
                                    <thead>
                                        <tr>
                                            <th><input type="checkbox" class="form-check-input checkbox-master"></th>
                                            <th class="sortableHeading" data-orderBy="name">Company Name</th>
                                            <th class="sortableHeading" data-orderBy="name">Type</th>
                                            <th class="sortableHeading" data-orderBy="city">Location</th>
                                            <th>Total Contacts</th>
                                            <th>Primary Contact</th>
                                            <th>Last Modified At</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-border-bottom-0">
                                    </tbody>
                                </table>
                            </div>
                            @if (\App\Models\Company::count('id'))
                                @if (canBulkDelete())
                                 <div class="mt-4">
                                    <button type="submit" class="btn btn-danger btn-sm" name="action" value="delete">Delete Selected</button>
                                    <button type="submit" class="btn btn-danger btn-sm confirm"
                                            data-message="This will remove all companies and their assotiated contacts. Are you sure you want to proceed?"
                                            name="action"
                                            value="deleteall">Delete All</button>
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

        <div id="bulkContactsModal"
             class="modal fade"
             tabindex="-1"
             role="dialog"
             aria-labelledby="modalTitleId"
             aria-hidden="true">
            <div class="modal-dialog"
                 role="document">
                <div class="modal-content">
                    <form method="post"
                          action="{{ sysRoute('companies.import') }}"
                          class=""
                          enctype="multipart/form-data"
                          data-notification-area="#companies-bulk-notification">
                        @csrf
                        <div class="modal-header">
                            <h5 id="modalTitleId"
                                class="modal-title"> Upload Bulk Contacts XLSX</h5>
                            <button type="button"
                                    class="btn-close"
                                    data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div id="companies-bulk-notification"></div>
                            <label>XLS File:</label>
                            <input type="file"
                                   class="form-control"
                                   name="file">
                            <p class="small mt-3">Note: Select XLS format file to upload. Please note that all the
                                defined fields should as exact on place on the file. <a
                                   href="{{ url('Contact-Sample-upload.xlsx') }}">Click here</a> to download the sample
                                file for your overview.</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button"
                                    class="btn btn-secondary"
                                    data-bs-dismiss="modal">Close</button>
                            <button type="submit"
                                    class="btn btn-dark">Upload</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </x-slot:modals>


    <x-slot:styles>
        <style type="text/css">
            .tags-select2-container .select2-container {
                min-width: 150px !important;
            }
        </style>
    </x-slot:styles>
</x-app-layout>
