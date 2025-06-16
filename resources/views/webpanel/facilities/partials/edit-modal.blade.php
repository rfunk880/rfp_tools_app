
<div class="modal-dialog" role="document">
    <form method="post" class="ajaxForm" data-close-modal="1" data-notification-area="#facility-notification"
    action="{{ sysRoute('facilities.update', encryptIt($model->id))}}">
        <input type="hidden" name="_method" value="PUT"/>

        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitleId">
                    Save Facility
                </h5>
                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                ></button>
            </div>
            <div class="modal-body">
                <div id="facility-notification"></div>
                <div class="form-group row">
                    <div class="col-md-12">
                        <label for="name">Facility Name*</label>
                        <input type="text" class="form-control" name="name" value="{{ @$model->name }}">
                        <x-input-error for="form.name" />
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-12">
                        <label for="owner">Owner*</label>
                        <input id="owner" type="text" class="form-control" name="owner"  value="{{ @$model->owner }}"/>
                        <x-input-error for="form.owner" />
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-12">
                        <label for="location">Address*</label>
                        <input id="location" type="text" class="form-control" name="location"  value="{{ @$model->location }}">
                        <x-input-error for="form.location" />
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-12">
                        <label for="is_key_account">Is Key Account</label>
                        <input type="hidden" name="is_key_account" value="0">
                        <input id="is_key_account" type="checkbox" class="form-check-input" name="is_key_account" value="1" {!! isChecked(1, $model->is_key_account) !!}>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button
                    type="button"
                    class="btn btn-secondary"
                    data-bs-dismiss="modal">
                    Close
                </button>
                <button type="submit" class="btn btn-dark">Save</button>
            </div>
        </div>
    </form>
    </div>
