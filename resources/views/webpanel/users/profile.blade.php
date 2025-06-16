<x-app-layout title="Profile">

    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-lg-12">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-center w-full">

                            <h5>Update your profile</h5>
                            @if(auth()->user()->getSwitchableRoles())
                            {{-- <form method="POST" action="{{ sysRoute('users.switch-role') }}"
                                  class="ms-auto">
                                <select class="form-select lazySelector submitOnChange" data-selected="{{ auth()->user()->user_type_id}}"
                                        style="width:200px;"
                                        name="user_type_id">
                                    <option value="">Switch Role</option>
                                    @foreach (auth()->user()->getSwitchableRoles() as $role)
                                        <option value="{{ $role }}">
                                            {!! @\App\Models\UserType::$typeLabel[$role] !!}
                                        </option>
                                    @endforeach
                                </select>
                            </form> --}}
                            @endif
                        </div>
                        <form class="form card-text"
                              novalidate="novalidate"
                              method="post"
                              action="<?php echo sysUrl('my/profile'); ?>"
                              role="form"
                              enctype="multipart/form-data">
                            <div class="mt-3">
                                <label for="name"
                                       class="form-label">Profile Image</label>
                                <input id="image"
                                       type="file"
                                       class="form-control"
                                       name="image">
                                @if ($image = $user->getMedia('avatar')->first())
                                    <img src="{{ $image->getThumbUrl() }}" />
                                    {{-- @dd($image->getRelativePath()) --}}
                                @endif
                            </div>
                            <div class="mt-3">
                                <label for="name"
                                       class="form-label">Full Name</label>
                                <input id="name"
                                       type="text"
                                       class="form-control"
                                       name="name"
                                       value="{{ $user->name }}">
                            </div>
                            <div class="mt-3">
                                <label for="email"
                                       class="form-label">Email Address</label>
                                <input id="email"
                                       type="text"
                                       class="form-control"
                                       name="email"
                                       value="{{ $user->email }}"
                                       readonly>
                            </div>
                            <div class="mt-3">
                                <label for="phone"
                                       class="form-label">Phone Number</label>
                                <input id="phone"
                                       type="text"
                                       class="form-control"
                                       name="phone"
                                       value="{{ $user->phone }}">
                            </div>

                            <div class="mt-4">
                                <button type="submit"
                                        class="btn rounded-pill btn-dark">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
