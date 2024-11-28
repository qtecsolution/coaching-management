@extends('layouts.master')

@section('title', 'Edit Material')

@section('content')
    <div class="page-heading">
        <x-page-title title="Edit Material" subtitle="" pageTitle="Edit Material" />

        <section class="section">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>

            <div class="card">
                {{-- <div class="card-header"><h5 class="card-title"></h5></div> --}}
                <div class="card-body">
                    <form action="{{ route('admin.class-materials.update', $classMaterial->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="batch" class="form-label">Batch<sup class="text-danger">*</sup></label>
                                    <select name="batch" id="batch" class="form-control form-select choice"
                                        data-selected-batch="{{ $classMaterial->batch_day->batch_id ?? '' }}" required>
                                        <option value="" selected disabled>Select Batch</option>
                                        @foreach ($batches as $batch)
                                            <option value="{{ $batch->id }}"
                                                {{ $batch->id == ($classMaterial->batch_day->batch_id ?? '') ? 'selected' : '' }}>
                                                {{ $batch->name }}
                                            </option>
                                        @endforeach
                                    </select>

                                    @error('batch')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="subject" class="form-label">Subject<sup class="text-danger">*</sup></label>
                                    <select name="subject" id="subject" class="form-control form-select choice"
                                        data-selected-subject="{{ $classMaterial->batch_day->id ?? '' }}" required>
                                        <option value="" selected disabled>Select Subject</option>
                                    </select>

                                    @error('subject')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="title" class="form-label">Title<sup class="text-danger">*</sup></label>
                                    <input type="text" name="title" id="title" placeholder="Title"
                                        class="form-control" value="{{ old('title', $classMaterial->title) }}" required>

                                    @error('title')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="resource_type" class="form-label">Resource Type<sup
                                            class="text-danger">*</sup></label>
                                    <select name="resource_type" id="resource_type" class="form-control form-select choice"
                                        required>
                                        <option value="File Upload"
                                            {{ old('resource_type', $classMaterial->is_file) == 1 ? 'selected' : '' }}>File
                                            Upload</option>
                                        <option value="URL"
                                            {{ old('resource_type', $classMaterial->is_file) == 0 ? 'selected' : '' }}>URL
                                        </option>
                                    </select>

                                    @error('subject')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group d-none" id="url">
                                    <label for="url" class="form-label">URL<sup class="text-danger">*</sup></label>
                                    <input type="text" name="url" id="url" placeholder="URL"
                                        class="form-control"
                                        value="{{ old('url', !$classMaterial->is_file ? $classMaterial->url : '') }}">

                                    @error('url')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="form-group d-none" id="file">
                                    <label for="file" class="form-label">File<sup class="text-danger">*</sup></label>
                                    <!-- File uploader with image preview -->
                                    @if ($classMaterial->is_file)
                                        <div>
                                            <a href="{{ absolutePath($classMaterial->url) }}" target="_blank"
                                                class="btn btn-primary mb-3">
                                                <i class="bi bi-eye"></i> View Existing File
                                            </a>
                                        </div>
                                    @endif
                                    <input type="file" name="file" class="basic-filepond"
                                        value="{{ $classMaterial->url }}">

                                    @error('file')
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
        // change resource type
        $('#resource_type').on('change', function() {
            if ($(this).val() == 'URL') {
                $('#url').removeClass('d-none');
                $('#file').addClass('d-none');

                // $('#url input').val('');
            } else {
                $('#url').addClass('d-none');
                $('#file').removeClass('d-none');

                // $('#file input').val('');
            }
        });

        $('#resource_type').trigger('change');

        // Update subjects when the batch dropdown is changed
        $('#batch').on('change', function() {
            const batchId = $(this).val();

            if (batchId) {
                $.ajax({
                    url: "{{ route('admin.class-materials.get-days') }}",
                    type: "POST",
                    data: {
                        batch: batchId,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        const $subject = $('#subject');

                        // Clear and populate the subject dropdown with new options
                        $subject.empty().append(
                            '<option value="" selected disabled>Select Subject</option>');

                        $.each(response.data, function(index, item) {
                            $subject.append(
                                `<option value="${item.id}">${item.subject_name}</option>`);
                        });

                        // If there's an existing selected subject, set it
                        const selectedSubjectId = $('#subject').data('selected-subject');
                        if (selectedSubjectId) {
                            $subject.val(selectedSubjectId);
                        }
                    },
                    error: function(error) {
                        console.error("Error fetching subjects:", error);
                    }
                });
            }
        });

        // change batch on page load
        $(document).ready(function() {
            // Get the selected batch and subject IDs from the data attributes
            const selectedBatchId = $('#batch').data('selected-batch');
            const selectedSubjectId = $('#subject').data('selected-subject');
            

            // If there's a selected batch, trigger the AJAX call to fetch subjects
            if (selectedBatchId) {
                $('#batch').val(selectedBatchId).trigger('change');

                // Fetch subjects for the selected batch on page load
                $.ajax({
                    url: "{{ route('admin.class-materials.get-days') }}",
                    type: "POST",
                    data: {
                        batch: selectedBatchId,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        const $subject = $('#subject');

                        // Clear and populate the subject dropdown with new options
                        $subject.empty().append(
                            '<option value="" selected disabled>Select Subject</option>');

                        $.each(response.data, function(index, item) {
                            $subject.append(
                                `<option value="${item.id}" ${selectedSubjectId == item.id ? 'selected' : ''}>${item.subject_name}</option>`);
                        });

                        // Set the existing subject as selected if it matches any of the new options
                        // if (selectedSubjectId) {
                        //     $subject.val(selectedSubjectId);
                        // }
                    },
                    error: function(error) {
                        console.error("Error fetching subjects:", error);
                    }
                });
            }
        });
    </script>
@endpush
