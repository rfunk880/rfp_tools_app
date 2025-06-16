
<div class="form-group row">
    <div class="col-md-6">
        <label for="subject">Reason for calling</label>
        <div class="input-group mb-3">
            <input type="hidden" name="subject_prefix" value="{{ $project->pn}}"/>
            <span class="input-group-text" id="prefixId">{{$project->pn}} :</span>
            <input type="text" name="subject" id="subject" class="form-control" value="{{ @$project->name }}" />
        </div>
    </div>

    {{-- <div class="col-md-6">
        <label for="type">Calllog Type</label>
        <select id="type" class="form-select" name="type">
            {!! arrayOptions(\App\Models\Calllog::$typeLabel) !!}
        </select>
    </div> --}}
</div>

<div class="form-group row" x-data="{companies: null}">
    <div class="col-md-6">
        <label for="company_id[]">Vendors</label>
        <select id="company_id[]" class="form-select companies select2" data-url='{{ sysUrl('ajax/contacts-by-companies?include=phone')}}'
                data-cascade=".contacts"
                name="company_id[]"
                x-model="companies"
                multiple>
            <option value="">Select</option>
            {!! OptionsView($project->companies, 'id', 'name') !!}
        </select>
    </div>

    <div class="col-md-6 contactsel d-none">
        <label for="contact_id[]">Send To</label>
        <select id="contact_id[]"
                class="form-select contacts" name="contact_id[]">
            <option value="">Choose Companies</option>
        </select>
    </div>
    {{-- <template x-if="companies && companies.length">
    </template> --}}
</div>

{{-- <div class="form-group row">
    <div class="col-md-6">
        <label for="user_id[]">CC</label>
        <select id="user_id[]" class="form-select select2" name="user_id[]" multiple>
            <option value="">Select</option>
            {!! OptionsView(\App\Models\User::active()->get(), 'id', 'name') !!}
        </select>
    </div>
</div> --}}

<div class="form-group row">
    <div class="col-md-12">
        <label for="content">Call log</label>
        <textarea id="content" class="form-control" name="content" style="height:300px;"></textarea>
    </div>
</div>

{{-- <div class="form-group row">
    <div class="col-md-6">
        <label for="files[]">Attachments</label>
        <input id="files[]" type="file" class="form-control" multiple name="files[]" placeholder="Files">
    </div>
</div> --}}

@push('stack-scripts')

<script>
    $(function(){
        $(".companies").on('change', function(e){
            const $cascade = $($(this).attr('data-cascade'));
            const $val = $(this).val();
            $(".contactsel").removeClass('d-none');
            $.post($(this).attr('data-url'), {
                'value': $val
            }).then(resp => {
                $cascade.empty().append(resp['body']);
                $cascade.select2({
                    placeholder: 'Select Contacts to send calllog'
                });
            })
        })
    })
</script>
@endpush