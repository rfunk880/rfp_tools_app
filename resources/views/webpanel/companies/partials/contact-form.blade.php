<div x-data="contactsUi">
    <div class="card-header d-flex justify-content-between">
        <div class="card-title mb-0">
            <h5 class="mb-0">Contacts</h5>
        </div>
        <div class="dropdown">
            <a href="#" @click.prevent="add">+Add</a>
        </div>
    </div>

    <div class="card-body">
        <div>
            <div>
                {{-- <div>
                    <div class="row">
                        <div class="col-md-2">Contact Name</div>
                        <div class="col-md-2">Email Address</div>
                        <div class="col-md-2">Phone</div>
                        <div class="col-md-2">Cell</div>
                        <div class="col-md-2">Location</div>
                        <div class="col-md-2">Title</div>
                        <div class="col-md-2">Notes</div>
                        <div class="col-md-2">Tags</div>
                        <div class="col-md-2">Primary</div>
                        <div class="col-md-2"></div>
                    </div>
                </div> --}}
                
                <tbody>
                    <template x-for="(item, index) in contacts" :key="index">
                        <div class="row">
                            <div class="col-md-2 mb-2">
                                <label>Name</label>
                                <input type="text" x-bind:name="`contacts[${index}][name]`"
                                       x-model="item.name"
                                       class="form-control" />
                            </div>
                            <div class="col-md-2 mb-2">
                                <label>Email</label>
                                <input type="email"
                                       x-bind:name="`contacts[${index}][email]`"
                                       x-model="item.email"
                                       class="form-control" />
                            </div>
                            <div class="col-md-2 mb-2">
                                <label>Phone</label>
                                <input type="phone"
                                       x-bind:name="`contacts[${index}][phone]`"
                                       x-model="item.phone"
                                       class="form-control"
                                       data-inputmask="'mask': '999-999-9999'" />
                            </div>
                            <div class="col-md-2 mb-2">
                                <label>Cell</label>
                                <input type="cell"
                                       x-bind:name="`contacts[${index}][cell]`"
                                       x-model="item.cell"
                                       class="form-control"
                                       data-inputmask="'mask': '999-999-9999'" />
                            </div>
                            <div class="col-md-2 mb-2">
                                <label>Location</label>
                                <input type="text"
                                       x-bind:name="`contacts[${index}][location]`"
                                       x-model="item.location"
                                       class="form-control" />
                            </div>
                            <div class="col-md-2 mb-2">
                                <label>Title</label>
                                <input type="text"
                                       x-bind:name="`contacts[${index}][title]`"
                                       x-model="item.title"
                                       class="form-control" />
                            </div>
                            <div class="col-md-2 mb-2">
                                <label>Notes</label>
                                <input type="notes"
                                       x-bind:name="`contacts[${index}][notes]`"
                                       x-model="item.notes"
                                       class="form-control" />
                            </div>
                            <div class="col-md-2 mb-2">
                                <label>Tags</label>
                                <select multiple
                                        class="form-select select2-tags"
                                        x-bind:name="`contacts[${index}][tags][]`"
                                        x-bind:data-select2-id="`contact_${index}`"
                                        x-model="item.tags"
                                        placeholder="">
                                    {!! OptionsView(\App\Models\Tag::all(), 'name', 'name') !!}
                                </select>
                            </div>
                            <div class="col-md-2 mb-2">
                                <label>Is Primary</label>
                                <input type="checkbox"
                                       x-bind:name="`contacts[${index}][is_primary]`"
                                       x-on:change="onPrimaryChange(index, $event)"
                                       x-model="item.is_primary"
                                       value="1" />
                            </div>
                            <div class="col-md-2 mb-2">
                                <a href="#" @click.prevent="contacts.splice(index, 1)">Remove</a>
                            </div>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>

    </div>
</div>
</div>

<x-slot:scripts>
    <script>
        function contactsUi() {
            return {
                init() {
                    console.log(window.contacts);
                },
                contacts: window['contacts'] || [{
                    name: '',
                    email: '',
                    phone: '',
                    title: '',
                    location: '',
                    cell: '',
                    notes: '',
                    is_primary: false,
                    tags: ''
                }],
                add() {
                    this.contacts.push({
                        name: '',
                        email: '',
                        phone: '',
                        title: '',
                        location: '',
                        cell: '',
                        notes: '',
                        tags: '',
                        is_primary: false,
                    });
                    this.$nextTick(() => initContactSelect2())
                },
                onPrimaryChange(index, e) {
                    // console.log(index, e.target.checked);
                    if (!e.target.checked) {
                        index = 0;
                    }
                    this.contacts = this.contacts.map((item, optIndex) => {
                        // console.log(item);
                        item.is_primary = optIndex == index;
                        return item;
                    });
                }
            }
        }
    </script>

    <script>
        function initContactSelect2() {
            $("[data-inputmask]").inputmask();
            $(".select2-tags").select2({
                tags: true
            });
        }
    </script>
</x-slot:scripts>
