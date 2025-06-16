<x-app-layout title="Update Contact">
    <div class="container-fluid flex-grow-1">
        <div class="row">
            <div class="col-lg-12">
                <div class="card mb-4">
                    <h5 class="card-header">Company Information
                      
                            <a href="{{ sysRoute('companies.index') }}"
                               class="btn btn-sm btn-primary float-end">Back to list</a>
                      
                               @if(canEdit())
                            <a href="{{ sysRoute('companies.edit', encryptIt($company->id)) }}"
                               class="btn btn-sm btn-warning float-end me-2">Edit</a>
                        @endif
                    </h5>
                    <div class="card-body">


                        <div>

                            <div class="card-body">
                                <div class="form-group row">
                                    <div class="col-md-4 mb-2">
                                        <label for="name">Company Name</label>
                                        <div class="form-group">
                                            {{ @$company->name }}
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <label for="phone">Main Phone</label>
                                        <div class="form-group">
                                            {{ formatPhoneNumber(@$company->phone) }}
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <label for="type">Type</label>
                                        <div class="form-group">
                                            {{ @$company->type }}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-4 mb-2">
                                        <label for="city">City</label>
                                        <div class="form-group">
                                            {{ @$company->city }}
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <label for="state">State</label>
                                        <div class="form-group">
                                            {{ @$company->state }}
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <label for="created_at">Created At</label>
                                        <div class="form-group">
                                            {{ $company->lastUpdatedAt() }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>



                    </div>
                    <div x-data="contactsUi">
                        <div class="card-header d-flex justify-content-between">
                            <div class="card-title mb-0">
                                <h5 class="mb-0">Contacts</h5>
                            </div>

                        </div>

                        <div class="card-body">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <td>SN</td>
                                        <td>Contact Name</td>
                                        <td>Email Address</td>
                                        <td style="width:180px;">Phone</td>
                                        <td style="width:180px;">Cell</td>
                                        <td style="width:200px;">Location</td>
                                        <td style="width:100px;">Title</td>
                                        <td style="width:280px;">Notes</td>
                                        <td style="width:300px;">Tags</td>
                                        <td style="width:50px;">Primary</td>

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($company->contacts as $k => $contact)
                                        <tr>
                                            <td>{{ $k + 1 }}</td>
                                            <td>
                                                {{ $contact->name }}

                                            </td>
                                            <td>
                                                {{ $contact->email }}
                                            </td>
                                            <td>
                                                {{ formatPhoneNumber($contact->phone) }}
                                            </td>
                                            <td>
                                                {{ formatPhoneNumber($contact->cell) }}
                                            </td>
                                            <td>{{ $contact->location }}</td>
                                            <td>{{ $contact->title }}</td>
                                            <td>{{ $contact->notes }}</td>
                                            <td>
                                                {{ $contact->tags->pluck('name')->join(', ') }}
                                            </td>
                                            <td>
                                                {{ $contact->is_primary ? 'Yes' : 'No' }}
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

</x-app-layout>
