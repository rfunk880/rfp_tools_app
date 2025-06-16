<x-app-layout title="Facilities">

    <div class="container-fluid flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-lg-8 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title header-elements">
                            <h5 class="m-0 me-2">Facility Management</h5>
                            <div class="card-title-elements ms-auto">
                                @livewire('projects.facility', [
                                    'facilityId' => null,
                                    'onlyAdd' => true,
                                ])
                                {{-- <form method="GET"
                                      class="d-flex w-full">
                                    <input type="text"
                                           class="form-control"
                                           name="keyword"
                                           value="{{ request('keyword') }}"
                                           placeholder="Search" />
                                </form> 
                                <a href="{{ url()->current() }}"
                                   class="btn btn-secondary">Reset</a> --}}
                            </div>
                        </div>
                        <form
                            method="POST"
                            action="{{ sysRoute('facilities.bulk-action') }}"
                        >
                            <div class="table-responsive">
                                <table
                                    class="table-striped deleteArena dTables table"
                                    data-url="<?php echo sysRoute('facilities.index'); ?>"
                                >
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th
                                                class="sortableHeading"
                                                data-orderBy="name"
                                            >Facility</th>
                                            <th>Owner</th>
                                            <th>Address</th>
                                            <th>Key Account</th>
                                            <th>Proj</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-border-bottom-0">
                                        <?php $i = 1; ?>
                                        @foreach ($facilities as $k => $facility)
                                            <tr class="deleteBox">
                                                <td>
                                                    <input
                                                        type="checkbox"
                                                        class="form-check-input checkbox-slaves"
                                                        name="ids[]"
                                                        value="{{ $facility->id }}"
                                                    >
                                                </td>
                                                <td>{{ @$facility->name }}</td>
                                                <td>{{ @$facility->owner }}</td>
                                                <td>{{ @$facility->location }}</td>
                                                <td>{!! $facility->is_key_account
                                                    ? '<i class="fa fa-check text-success"></i>'
                                                    : '<i class="fa fa-multiply text-danger"></i>' !!}</td>
                                                <td>{{ @$facility->projects->count() }}</td>

                                                <td class="text-end">

                                                    {{-- @can(\App\Module::CONTACTS_EDIT)
                <a href="<?php echo sysRoute('facilities.live-edit', encryptIt($facility->id)); ?>" title="Edit Facility" class="btn-sm me-1">
                    <i class="ti ti-edit"></i>
                </a>
            @endcan --}}
                                                    @if (canEdit())
                                                        <a
                                                            title="Edit"
                                                            href="<?php echo sysRoute('facilities.edit', encryptIt($facility->id)); ?>"
                                                            class="btn-sm modalFetcher me-1"
                                                            data-target=".footerModal"
                                                        >
                                                            <i class="ti ti-edit"></i>
                                                        </a>
                                                        @if ($facility->projects->count() == 0)
                                                            <a
                                                                title="Delete Facility"
                                                                href="#"
                                                                class="btn-sm ajaxdelete me-1"
                                                                data-id="<?php echo $facility->id; ?>"
                                                                data-url="<?php echo sysUrl('facilities/delete/' . encryptIt($facility->id)); ?>"
                                                                data-token="<?php echo urlencode(md5($facility->id)); ?>"
                                                            >
                                                                <i class="text-danger ti ti-trash"></i>
                                                            </a>
                                                        @endif
                                                    @endif
                                                </td>
                                            </tr>
                                            <?php $i++; ?>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @if (canBulkDelete())
                                <button
                                    type="submit"
                                    name="action"
                                    value="delete"
                                    class="btn btn-primary"
                                >Delete Selected</button>
                            @endif

                        </form>
                        <nav
                            id="paginationWrapper"
                            class="text-dark p-3"
                        ></nav>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card deleteArena">
                    <div class="card-body">
                        <div class="card-title header-elements">
                            <h5 class="m-0 me-2">Manage Tags</h5>
                            @if (canBulkDelete())
                                <a
                                    class="btn btn-sm btn-primary confirm ms-auto"
                                    data-message="Are you sure you want to delete all unused tags?"
                                    href="{{ sysUrl('facilities/delete-tags') }}"
                                >Delete Unused Tags</a>
                            @endif
                        </div>
                        <div class="table-responsive">
                            <table class="table-striped deleteArena dTables table">
                                <thead>
                                    <tr>
                                        <th>SN</th>
                                        <th
                                            class="sortableHeading"
                                            data-orderBy="name"
                                        >Tags</th>
                                        <td>Contacts</td>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="table-border-bottom-0">

                                    @foreach (\App\Models\Tag::with('contacts')->get() as $i => $tag)
                                        <tr class="deleteBox">
                                            <td>{{ $i + 1 }}</td>
                                            <td class="tag-item-replace">
                                                {{ $tag->name }}
                                            </td>
                                            <td>{{ $tag->contacts->count() }}</td>
                                            <td>
                                                <a
                                                    title="Edit Tag"
                                                    href="#"
                                                    class="tag-item ms-1"
                                                    data-url="{{ sysUrl('ajax/update-tag/' . encryptIt($tag->id)) }}"
                                                    data-val="{{ $tag->name }}"
                                                >
                                                    <i class="text-primary ti ti-edit"></i>
                                                </a>
                                                <a
                                                    title="Delete Tag"
                                                    href="#"
                                                    class="ajaxdelete ms-1"
                                                    data-url="<?php echo sysUrl('ajax/tags/delete/' . encryptIt($tag->id)); ?>"
                                                    data-token="<?php echo urlencode(md5($tag->id)); ?>"
                                                >
                                                    <i class="text-danger ti ti-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <x-slot:scripts>
        <script>
            $(function() {
                $(document).on('click', '.tag-item', function(e) {
                    let $elm = $(this);
                    let val = prompt('Enter your tag name', $elm.attr('data-val'));
                    if (val) {
                        $.post($elm.attr('data-url'), {
                            value: val
                        }, function(response) {
                            processResponse(response, $elm);
                            if (response['success']) {
                                $elm.closest('tr').find('.tag-item-replace').text(val);
                            }
                        })
                    }
                })
            })
        </script>

    </x-slot:scripts>

</x-app-layout>
