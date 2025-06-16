<x-app-layout title="Roles">
    <div class="container-fluid flex-grow-1 container-p-y">

        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Roles</h5>
                <p class="card-text">
                <div method="post" action="{{ sysRoute('roles.store') }}">
                    @csrf
                    <div id="sortable_roles_container" class="d-flex gap-2" data-url="{{ url('roles/update-position') }}">

                        @foreach ($roles as $role)
                            <div id="role-{{ $role->id }}" type="button">
                                <span class="fw-bold me-2"> {{ $role->name }}</span>
                                <a href="{{ sysRoute('roles.delete', encryptIt($role->id)) }}"
                                   class="text-small confirm text-info ml-2"
                                   data-message="Are you sure you want to remove this role?">
                                    <i class="fa fa-trash"></i>
                                </a>
                            </div>
                        @endforeach

                        <button class="btn btn-dark btn-sm"
                                data-bs-toggle="modal"
                                data-bs-target="#addRoleModal">
                            Add Role
                        </button>
                    </div>
                </div>
            </div>
        </div>


        <form method="post" class="ajaxForm" data-notification-animation="1" action="{{ sysRoute('roles.bulk-update') }}">
            @csrf

            <div class="card mb-3">
                <div class="row mb-3 p-4">
                    <div class="col-12 col-lg-9">
                        <h4>Permissions</h4>
                        <p class="big">
                            By default, Admin can view, update and delete any Module, You can grant other users extra
                            permissions, such as the ability to perform actions on all modules.
                        </p>
                    </div>
                    <div class="col-auto ml-auto">
                        <input type="submit" class="btn btn-dark ml-2" name="save" value="Update Roles">
                    </div>
                </div>

                <div class="w-100 position-relative overflow-auto">
                    <table class="table-hover tableFixHead roles_table table">
                        <thead class="sticky-top bg-white"
                               style="top:0;">
                            <tr>
                                <th>Permissions</th>
                                @foreach ($roles as $role)
                                    <th class="text-wrap text-left">{{ $role->name }}</th>
                                @endforeach
                            </tr>
                        </thead>

                        @foreach ($modules->groupBy('module') as $module => $permissions)
                            <tbody>
                                <tr class="bg-light tr_header ">
                                    <td width="20%" class="">{{ $module }}</td>
                                    @foreach ($roles as $index => $role)
                                        <td class="td_permission_checkbox">
                                            <input type="checkbox"
                                                   class="check_all css-checkbox"
                                                   data-index="{{ $index }}"
                                                   title="Check All">
                                        </td>
                                    @endforeach
                                </tr>
                                @foreach ($permissions as $permission)
                                    <tr>
                                        <td>
                                            {{ $permission->label }}
                                            @if (!is_null(@$permission->help_text))
                                                <br>
                                                <small>{{ $permission->help_text }}</small>
                                            @endif
                                        </td>
                                        @foreach ($roles as $role)
                                            <td class="td_permission_checkbox">
                                                <label class="text-wrap">
                                                    <input type="checkbox"
                                                           name="permissions[{{ $role->id }}][{{ $permission->name }}]"
                                                           {!! isMultiChecked($permission->name, @$role->permissions->pluck('name')->toArray()) !!}
                                                           class="css-checkbox"
                                                           value="1">
                                                </label>
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        @endforeach
                    </table>

                </div>
            </div>
            <input type="submit" class="btn btn-dark" name="save" value="Update Roles">
        </form>
    </div>


    <x-slot:modals>
        <div id="addRoleModal"
             class="modal fade"
             tabindex="-1"
             role="dialog"
             aria-labelledby="modalTitleId"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form method="post" action="{{ sysRoute('roles.store') }}">
                        @csrf
                        <div class="modal-header">
                            <h5 id="modalTitleId" class="modal-title"> Add New Role*</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"  aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-dark">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </x-slot:modals>

    <x-slot:styles>
        <style type="text/css">
            .btn.sortable_placeholder {
                width: 100px;
                height: 30px;
                border: 1px dashed #333333;
            }
        </style>
    </x-slot:styles>

    <x-slot:scripts>
        <script>
            (function($, window, undefined) {
                $(function() {
                    /* check all permission group */
                    $(".check_all").on('change', function(e) {
                        var $tr = $(this).closest('.tr_header');
                        var checked = $(this).is(":checked");
                        var index = parseInt($(this).attr('data-index')) + 1;
                        var nextHeaderFound = false;
                        while ((!nextHeaderFound && $tr.next('tr').length > 0)) {
                            $row = $tr.next('tr');
                            if ($row.hasClass('tr_header')) {
                                $nextHeaderFound = true;
                                break;
                            }
                            $row.find('td').eq(index).find('[type=checkbox]').prop('checked', checked)
                                .trigger('change');
                            /* update $tr for next row */
                            $tr = $row;
                        }
                        $tr.next('tr').each(function(i, v) {
                            var $row = $(v);
                        });
                    });
                });
            })(jQuery, window);
        </script>
    </x-slot:scripts>

</x-app-layout>