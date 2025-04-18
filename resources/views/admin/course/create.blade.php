@extends('layouts.master')

@section('title', 'Add Course')

@section('content')
    <div class="page-heading">
        <x-page-title title="Add Course" />

        <section class="section">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.courses.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="file" class="form-label">Image<sup class="text-danger">*</sup></label>
                                    <!-- File uploader with image preview -->
                                    <input type="image" name="image" accept="image/*" class="basic-filepond" required>

                                    @error('image')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6"></div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="title" class="form-label">Title<sup class="text-danger">*</sup></label>
                                    <input type="text" name="title" id="title" placeholder="Title"
                                        class="form-control" value="{{ old('title') }}" required>

                                    @error('title')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label for="description" class="form-label">Description<sup
                                            class="text-danger">*</sup></label>
                                    <textarea name="description" id="description" rows="5" class="form-control" placeholder="Description" required>{{ old('description') }}</textarea>

                                    @error('description')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
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
        $(document).ready(function() {
            $('.select2').select2();

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
        });
    </script>
@endpush
