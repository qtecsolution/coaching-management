@extends('layouts.master')

@section('title', 'Generate Payments')

@section('content')
<div class="page-heading">
    <x-page-title title="Generate Payments" subtitle="" pageTitle="Generate Payments" />

    <section class="section">
        <div class="card">
            <div class="card-body">
                <div class="alert alert-light-info color-info">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-info-circle-fill" viewBox="0 0 16 16">
                        <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16m.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2" />
                    </svg> The tuition fees for all active batches are automatically generated each month through a scheduled process. However, if the scheduler fails to execute for any reason, you can manually generate tuition fees for a specific month using the form below. Generated payments can be viewed under the 'Payment Due' section.
                </div>
                <form action="{{ route('admin.payments.generate') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="month" class="form-label">Month<sup
                                        class="text-danger">*</sup></label>
                                <input type="month" name="month" id="month"
                                    placeholder="Enter Month" class="form-control"
                                    max="{{ now()->format('Y-m') }}"
                                    value="{{ old('month', now()->format('Y-m')) }}"
                                    required>

                                @error('month')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12 mt-2">
                            <button type="submit" class="btn btn-primary">Generate</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>
@endsection

@push('js')
<script></script>
@endpush