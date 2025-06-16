<div x-data="{ user_type_id: {{ @$user->user_type_id ? $user->user_type_id : 0 }}, switch_roles: {{ @$user && $user->getSwitchableRoles() ? 'true' : 'false' }} }">

    <div class="row mb-3">
        <div class="col-6">
            <label class="form-label">User Type</label>
            <select class="form-select"
                    name="user_type_id"
                    x-model="user_type_id"
                    data-toggle-value="{{ \App\Models\UserType::ESTIMATOR }}">
                <option value="">Select type</option>
                {!! arrayOptions(\App\Models\UserType::PermitedGroupArray(), @$user->user_type_id) !!}
            </select>
        </div>

        <template x-if="user_type_id != {{ \App\Models\UserType::ADMIN }}">
            <div class="col-6">
                <label class="form-label"> Role</label>
                <select class="form-select"
                        name="role_id">
                    <option value="">Select type</option>
                    {!! OptionsView(\App\Models\Role::all(), 'name', 'name', @$user ? @$user->roles()->first()->name : '') !!}
                </select>
            </div>
        </template>
    </div>

    <div class="mb-3">
        <label class="form-label">Full Name</label>
        <input id="name"
               type="text"
               class="form-control"
               name="name"
               value="{{ @$user->name }}">
    </div>

    <div class="mb-3">
        <label class="form-label">Email Address</label>
        <input id="email"
               type="text"
               name="email"
               value="{{ @$user->email }}"
               class="form-control">
    </div>

    <div class="mb-3">
        <label class="form-label">Phone Number</label>
        <input id="phone"
               type="text"
               class="form-control phone-mask"
               name="phone"
               value="{{ @$user->phone }}">
    </div>

    <div class="mb-3">
        <label class="form-label">Password</label>
        <input id="password"
               type="password"
               class="form-control"
               name="password">
    </div>

    <div class="mb-3">
        <label class="form-label">Confirmation Password</label>
        <input id="password_confirmation"
               type="password"
               class="form-control"
               name="password_confirmation">
    </div>

    <div class="mb-3">
        <div class="form-check">
            <input id="status_1"
                   type="radio"
                   name="status"
                   value="1"
                   class="form-check-input"
                   <?php echo isChecked(1, @$user->status); ?> />
            <label class="form-check-label"
                   for="status_1">Active</label>
        </div>
        <div class="form-check">
            <input id="status_2"
                   type="radio"
                   class="form-check-input"
                   name="status"
                   value="0"
                   <?php echo isChecked(0, @$user->status); ?> />
            <label class="form-check-label"
                   for="status_2">Inactive</label>
        </div>
    </div>
    @if (@$user && auth()->user()->isAdmin())
        {{-- <div class="mb-3">
            <div class="form-check">
                <input id="switch_roles_1"
                       type="checkbox"
                       name="switch_roles"
                       value="1"
                       x-model="switch_roles"
                       class="form-check-input" />
                <label class="form-check-label"
                       for="switch_roles_1">Allow to switch roles</label>
            </div>

            <div class="row">

                <div class="col-6"
                     x-bind:class="{ 'd-none': !switch_roles, 'd-block': switch_roles }">
                    <label class="form-label"> Which types can this user switch to?</label>
                    <div>
                        <select class="form-select select2"
                                style="width:100%;"
                                multiple
                                name="switchable_user_types[]">
                            <option value="">Select type</option>
                            {!! arrayOptions(
                                \App\Models\UserType::PermitedGroupArray(true),
                                @$user->getSwitchableRoles(),
                            ) !!}
                        </select>
                    </div>
                </div>
            </div>
        </div> --}}
    @endif
</div>
