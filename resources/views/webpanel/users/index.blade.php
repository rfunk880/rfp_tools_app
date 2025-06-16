<x-app-layout title="Users">

    <div class="container-fluid flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-lg-12 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title header-elements">
                            <h5 class="m-0 me-2">User Management</h5>
                            <div class="card-title-elements ms-auto">
                                <form method="GET">
                                    <input type="text" class="form-control" name="keyword" value="{{ request('keyword') }}" placeholder="Search" />
                                </form>
                                @if(isManagement())
                                    <a href="{{ sysRoute('users.create') }}" class="btn btn-dark"><span class="tf-icon ti ti-plus ti-xs me-1"></span>New User</a>
                                @endif
                                <a href="{{ url()->current() }}" class="btn btn-secondary">Reset</a>
                                {{-- @if (authUser()->isAdmin())
                                    <a href="{{ sysRoute('roles.index') }}" class="btn btn-dark">Roles</a>
                                @endif --}}
                            </div>
                        </div>
                        <div class="table-responsive">
                        <table class="table-striped deleteArena ajaxTable table" data-url="<?php echo sysRoute('users.index'); ?>">
                            <thead>
                            <tr>
                                <th class="sortableHeading" data-orderBy="name">Full Name</th>
                                <th data-orderBy="email">Email Address</th>
                                <th>User Type</th>
                                <th>Created Date</th>
                                <th>Last Login At</th>
                                <th>Status</th>
                                <th>Actions</th>
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