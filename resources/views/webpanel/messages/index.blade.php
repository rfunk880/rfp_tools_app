<x-app-layout title="Project Messages">
    <div class="container-fluid flex-grow-1 container-p-y">

        <div class="row">
            <div class="col-lg-12 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title header-elements">
                            <h5 class="m-0 me-2">Project Messages</h5>
                        </div>
                        @include('webpanel.messages.partials.filter')

                        <form method="post"
                              action="{{ sysRoute('messages.bulk-action') }}">
                            <div class="table-responsive">
                                <table class="table-striped deleteArena ajaxTable table"
                                       data-url="<?php echo sysRoute('messages.index'); ?>">
                                    <thead>
                                        <tr>
                                            <th style="width:50px;">&nbsp;</th>
                                            <th class="sortableHeading" data-orderBy="subject" style="width:200px;">Subject</th>
                                            <th style="width:100px;">Project</th>
                                            <th style="width:400px;">Recepients</th>
                                            <th style="width:100px;">Sent At</th>
                                            <th style="width:50px;">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-border-bottom-0">
                                    </tbody>
                                </table>
                            </div>
                            <div>
                               @if(canBulkDelete())
                                <button type="submit" class="btn btn-dark btn-sm mb-3 mt-2" name="action" value="delete">Delete Selected</button>
                                <br/>
                                @endif
                                 <nav id="paginationWrapper"></nav>
                              
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
