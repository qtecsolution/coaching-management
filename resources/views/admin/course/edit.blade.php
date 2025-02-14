@extends('layouts.master')

@section('title', 'Add Course')

@section('content')
    <div class="page-heading">
        <x-page-title title="Add Course" />

        <section class="section">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.courses.update', $course->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="file" class="form-label">Image<sup class="text-danger">*</sup></label>
                                    <!-- File uploader with image preview -->
                                    {{-- TODO: How to validate if image field is empty. --}}
                                    <input type="image" name="image" accept="image/*" class="basic-filepond" data-source="{{ asset('storage/' . $course->image) }}">

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
                                        class="form-control" value="{{ old('title', $course->title) }}" required>

                                    @error('title')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label for="description" class="form-label">Description<sup class="text-danger">*</sup></label>
                                    <textarea name="description" id="description" rows="5" class="form-control" placeholder="Description" required>{{ old('description', $course->description) }}</textarea>

                                    @error('description')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="d-flex align-items-center mt-2">
                            <div class="col-6">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" name="status" type="checkbox" @checked($course->status) value="1" role="switch" id="status">
                                    <label class="form-check-label" for="status">Active</label>
                                </div>
                            </div>
                            <div class="col-6 text-end">
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
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
