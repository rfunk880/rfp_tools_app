<x-app-layout title="Bulk Edit Projects">

    <div class="container-fluid flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-lg-12 mb-4">
                <div class="card">
                    <div class="card-body">
                    
                    <div class="card-title header-elements">
                            <h5 class="fw-bold m-0 me-2">Bulk Edit Projects</h5>
                            <div class="card-title-elements ms-auto">
                               	<a href="{{url('webpanel/projects')}}" class="btn btn-primary">Back to Projects</a>
                            </div>
                        </div>
                        
                        <form method="POST" action="{{ sysRoute('projects.bulk-update') }}">
                            <div class="table-responsive" style="overflow-x: scroll;">
                                <table class="deleteArena" data-url="<?php echo sysRoute('projects.index'); ?>" style="width:3000px">
                                    <thead>
                                        <tr>
                                            <th style="width:350px;">Name</th>
                                            <th style="width:250px;">Site Visit</th>
                                            <th style="width:250px;">Bid Due</th>
                                            <th style="width:250px;">Subcontractor Bid Due</th>
                                            <th style="width:250px;">Bid Document</th>
                                       
                                            <th style="width:250px;">Estimated Start Date</th>
                                            <th style="width:250px;">Estimated End Date</th>
                                            <th style="width:250px;">Final Estimate</th>
                                            <th style="width:250px;">Duration (days)</th>
                                            <th style="width:250px;">Public Notes</th>
                                            <th style="width:250px;">Internal Notes</th>
                                            <th style="width:250px;">Probability</th>
                                            <th style="width:250px;">PO Status</th>
                                            <th style="width:250px;">Awarded Date</th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-border-bottom-0">
                                        @foreach($projects as $project)
                                            <livewire:projects.row-edit :project="$project" wire:key="{{ $project->id.'-project'}}"/>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
               
                         
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
