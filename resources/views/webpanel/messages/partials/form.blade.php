<div class="form-group row">
    <div class="col-md-6">
        <label for="subject">Subject</label>
        <div class="input-group mb-3">
            <input type="hidden"
                   name="subject_prefix"
                   value="{{ $project->pn }}" />
            <span id="prefixId"
                  class="input-group-text">{{ $project->pn }} :</span>
            <input id="subject"
                   type="text"
                   name="subject"
                   class="form-control"
                   value="{{ @$project->name }}" />
        </div>
    </div>

    <div class="col-md-6">
        <label for="type">Message Type</label>
        <select id="type"
                class="form-select"
                name="type">
            {!! arrayOptions(\App\Models\Message::$typeLabel) !!}
        </select>
    </div>
</div>

<div class="form-group row" x-data="{ companies: null, sendToAll: false }">
    <div class="col-md-6">
        <div class="d-flex">
            <label for="send_to_all[]">Vendors</label>
            <label for="send_to_all"
                   class="ms-2">
                <input type="checkbox"
                       name="send_to_all"
                       value="1"
                       x-model="sendToAll" />
                Send To All
            </label>
        </div>
        
        <select id="contact_id"
                class="form-select contacts select2"
                data-url='{{ sysUrl('ajax/contacts-by-companies') }}'
                data-cascade=".contacts"
                name="contact_id[]"
                x-model="companies"
                data-placeholder="Select Vendor Contacts"
                {{-- x-bind:disabled="sendToAll" --}}
                multiple>
            {{-- <option value="">Select</option> --}}
            {!! OptionsView(
                \App\Models\Contact::whereIn('id', $project->getEligibleContactsForMessage())->with('company')->get(),
                'id',
                function ($item) {
                    return @$item->company->name . ' &lt;' . $item->name . '&gt;';
                },
            ) !!}
        </select>
    </div>

    <div class="col-md-6 contactsel">
        <label for="send_to_emails[]">Additional emails</label>
        <select id="send_to_emails[]"
                class="form-select select2-tags"
                multiple
                name="send_to_emails[]"
                data-placeholder="Type additional emails">
        </select>
        {{-- <p class="sm">"," separated emails</p> --}}
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
        <label for="content">Message Body</label>
       <input id="content" value="" type="hidden" name="content">
  		<trix-editor
          style="overflow-y:auto;height: 260px;"   class="trix-content"
        input="content" ></trix-editor>               
    </div>
</div>

<div class="form-group row">
    <div class="col-md-6">
        <label for="files[]">Attachments</label>
        <input id="files[]"
               type="file"
               class="form-control"
               multiple
               name="files[]"
               placeholder="Files">
    </div>
</div>

@push('stack-scripts')
    <script>
        $(function() {
            $("[name=send_to_all]").on('change', function(e) {
                if ($(this).is(":checked")) {
                    $('#contact_id').select2('destroy').find('option').prop('selected', 'selected').end()
                        .select2();
                } else {
                    $('#contact_id').select2('destroy').find('option').prop('selected', false).end().select2();
                }
            });
            $(".companies").on('change', function(e) {
                const $cascade = $($(this).attr('data-cascade'));
                const $val = $(this).val();
                $(".contactsel").removeClass('d-none');
                $.post($(this).attr('data-url'), {
                    'value': $val
                }).then(resp => {
                    $cascade.empty().append(resp['body']);
                    $cascade.select2({
                        placeholder: 'Select Contacts to send message'
                    });
                })
            })
        })
    </script>
@endpush
