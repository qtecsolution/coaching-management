@extends('layouts.master')

@section('title', 'Edit Lead')

@section('content')
    <div class="page-heading">
        <x-page-title title="Edit Lead" :url="route('admin.leads.index')" />

        <section class="section">
            <div class="card">
                {{-- <div class="card-header"><h5 class="card-title"></h5></div> --}}
                <div class="card-body">
                    <form action="{{ route('admin.leads.update', $lead->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name" class="form-label">Name<sup class="text-danger">*</sup></label>
                                    <input type="text" name="name" id="name" placeholder="Name"
                                        class="form-control" value="{{ old('name', $lead->name) }}" required>

                                    @error('name')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone" class="form-label">Phone<sup class="text-danger">*</sup></label>
                                    <input type="tel" name="phone" id="phone" placeholder="Phone"
                                        class="form-control" value="{{ old('phone', $lead->phone) }}" required>

                                    @error('phone')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" name="email" id="email" placeholder="Email"
                                        value="{{ old('email', $lead->email) }}" class="form-control">

                                    @error('email')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="status" class="form-label">Status</label>
                                    <select name="status" id="status" class="form-control form-select choice">
                                        <option value="" selected disabled>Select Status</option>
                                        <option value="0" {{ $lead->status == 0 ? 'selected' : '' }}>Pending</option>
                                        <option value="1" {{ $lead->status == 1 ? 'selected' : '' }}>Done</option>
                                    </select>

                                    @error('status')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="note" class="form-label">Note</label>
                                    <textarea name="note" id="note" rows="5" class="form-control" placeholder="note">{{ old('note', $lead->note) }}</textarea>

                                    @error('note')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="col-12 mt-3">
                            <fieldset class="border rounded p-3" id="dynamic-fields">
                                <legend>Dynamic Fields</legend>

                                <div class="col-12 text-end mb-3">
                                    <button class="btn btn-primary" type="button" id="add-field">
                                        <i class="bi bi-plus"></i> Add
                                    </button>
                                </div>

                                <!-- Dynamic Fields Container -->
                                <div id="fields-container">
                                    @foreach ($lead->dynamicFields as $field)
                                        <div class="row align-items-end mb-3">
                                            <div class="col-md-5">
                                                <div class="form-group">
                                                    <label class="form-label">Field Name<sup
                                                            class="text-danger">*</sup></label>
                                                    <input type="text" name="field_name[]" value="{{ $field->name }}"
                                                        placeholder="Field Name" class="form-control" required>
                                                </div>
                                            </div>
                                            <div class="col-md-5">
                                                <div class="form-group">
                                                    <label class="form-label">Field Value<sup
                                                            class="text-danger">*</sup></label>
                                                    <input type="text" name="field_value[]" value="{{ $field->value }}"
                                                        placeholder="Field Value" class="form-control" required>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <button type="button" class="btn btn-danger remove-field">
                                                        <i class="bi bi-dash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </fieldset>
                        </div>

                        <div class="col-12 text-end mt-2">
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('js')
    <script>
        const addFieldButton = document.getElementById('add-field');
        const fieldsContainer = document.getElementById('fields-container');

        // Add new field group
        addFieldButton.addEventListener('click', () => {
            const fieldGroup = document.createElement('div');
            fieldGroup.classList.add('row', 'align-items-end', 'mb-3');
            fieldGroup.innerHTML = `
                    <div class="col-md-5">
                        <div class="form-group">
                            <label class="form-label">Field Name<sup class="text-danger">*</sup></label>
                            <input type="text" name="field_name[]" placeholder="Field Name"
                                class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                            <label class="form-label">Field Value<sup class="text-danger">*</sup></label>
                            <input type="text" name="field_value[]" placeholder="Field Value"
                                class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <button type="button" class="btn btn-danger remove-field">
                                <i class="bi bi-dash"></i>
                            </button>
                        </div>
                    </div>
                `;

            fieldsContainer.appendChild(fieldGroup);
        });

        // Remove field group
        fieldsContainer.addEventListener('click', (e) => {
            if (e.target.closest('.remove-field')) {
                // let totalElements = document.querySelectorAll('.remove-field').length;
                // if (totalElements < 2) {
                //     alert('You cannot remove more fields.');
                //     return;
                // }

                const fieldGroup = e.target.closest('.row');
                fieldsContainer.removeChild(fieldGroup);
            }
        });
    </script>
@endpush
