@extends('layouts.master')

@section('title', 'Add Material')

@section('content')
    <div class="page-heading">
        <x-page-title title="Add Material" subtitle="" pageTitle="Add Material" />

        <section class="section">
            <div class="card">
                {{-- <div class="card-header"><h5 class="card-title"></h5></div> --}}
                <div class="card-body">
                    <form action="{{ route('admin.class-materials.store') }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="class" class="form-label">Batch<sup class="text-danger">*</sup></label>
                                    <select name="class" id="class" class="form-control form-select choice" required>
                                        <option value="" selected disabled>Select Batch</option>
                                        @foreach ($batches as $batch)
                                            <option value="{{ $batch->name }}"
                                                {{ old('batch') == $batch->name ? 'selected' : '' }}>{{ $batch->name }}
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
                                    <label for="class" class="form-label">Subject<sup class="text-danger">*</sup></label>
                                    <select name="class" id="class" class="form-control form-select choice" required>
                                        <option value="" selected disabled>Select Subject</option>
                                        @foreach ($subjects as $subject)
                                            <option value="{{ $subject->name }}"
                                                {{ old('subject') == $subject->name ? 'selected' : '' }}>
                                                {{ $subject->name }}</option>
                                        @endforeach
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
                                        class="form-control" value="{{ old('title') }}" required>

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
                                        <option value="File Upload">File Upload</option>
                                        <option value="URL">URL</option>
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
                                        class="form-control" value="{{ old('url') }}" required>

                                    @error('url')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="form-group d-none" id="file">
                                    <label for="file" class="form-label">File<sup class="text-danger">*</sup></label>
                                    <!-- File uploader with image preview -->
                                    <input type="file" name="file" accept="image/*" class="image-preview-filepond">

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
        $('#resource_type').on('change', function() {
            if ($(this).val() == 'URL') {
                $('#url').removeClass('d-none');
                $('#file').addClass('d-none');

                $('#url input').val('');
            } else {
                $('#url').addClass('d-none');
                $('#file').removeClass('d-none');

                $('#file input').val('');
            }
        });

        $('#resource_type').trigger('change');
    </script>
@endpush