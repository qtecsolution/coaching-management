@extends('layouts.master')

@section('title', 'Add Course')

@section('content')
    <div class="page-heading">
        <x-page-title title="Add Course" subtitle="" pageTitle="Add Course" />

        <section class="section">
            <div class="card">
                {{-- <div class="card-header"><h5 class="card-title"></h5></div> --}}
                <div class="card-body">
                    <form action="{{ route('admin.courses.update', $course->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="file" class="form-label">Image<sup class="text-danger">*</sup></label>
                                    <!-- File uploader with image preview -->
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
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="price" class="form-label">Price<sup class="text-danger">*</sup></label>
                                    <input type="number" name="price" id="price" placeholder="Price"
                                        class="form-control" value="{{ old('price', $course->price) }}" required>

                                    @error('price')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="discount_type" class="form-label">Discount Type<sup class="text-danger">*</sup></label>
                                    <select name="discount_type" id="discount_type" class="form-control form-select">
                                        <option value="fixed" {{ old('fixed', $course->discount_type) == 'fixed' ? 'selected' : '' }}>Fixed</option>
                                        <option value="percentage" {{ old('percentage', $course->discount_type) == 'percentage' ? 'selected' : '' }}>Percentage</option>
                                    </select>

                                    @error('discount_type')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="discount" class="form-label">Discount<sup class="text-danger">*</sup></label>
                                    <input type="number" name="discount" id="discount" placeholder="Discount"
                                        class="form-control" value="{{ old('discount', $course->discount) }}" required>

                                    @error('discount')
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
