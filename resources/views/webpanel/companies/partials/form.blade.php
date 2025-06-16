<div>
    <h6>Company Information</h6>
    <div class="card-body">
        <div class="form-group row">
            <div class="col-md-4 mb-2">
                <label for="name">Company Name</label>
                <div class="form-group">
                    <input id="name" type="text" class="form-control"  name="name" value="{{ @$model->name }}">
                </div>
            </div>
            <div class="col-md-4 mb-2">
                <label for="phone">Main Phone</label>
                <div class="form-group">
                    <input id="phone" type="text" class="form-control" data-inputmask="'mask': '999-999-9999'"  name="phone" value="{{ @$model->phone }}">
                </div>
            </div>
            <div class="col-md-4 mb-2">
                <label for="type">Type</label>
                <div class="form-group">
                    <select id="type" type="text" class="form-select" name="type" value="{{ @$model->type }}">
                        <option value="">Select</option>
                        {!! arrayOptions(\App\Models\Company::$TYPES, @$model->type, false) !!}
                    </select>
                </div>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-md-4 mb-2">
                <label for="city">City</label>
                <div class="form-group">
                    <input id="city" type="text" class="form-control" name="city" value="{{ @$model->city }}">
                </div>
            </div>
            <div class="col-md-4 mb-2">
                <label for="state">State</label>
                <div class="form-group">
                    <select id="state" type="text" class="form-select lazySelector" name="state" data-selected="{{ @$model->state }}">
                        <option value="">Select</option>
                        @include('elements.us-state-options')
                    </select>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="notes">Notes</label>
                <div class="form-group">
                    <textarea id="notes" type="text" class="form-control" name="notes" >{{ @$model->notes }}</textarea>
                </div>
        </div>
    </div>
</div>