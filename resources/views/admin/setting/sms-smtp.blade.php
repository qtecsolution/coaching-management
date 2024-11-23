@extends('layouts.master')

@php
    use function App\Http\Helpers\smsProviderData;

    $title = Str::replace('-', ' ', Str::upper($type));
@endphp
@section('title', 'Edit ' . $title . ' Settings')

@section('content')
    <div class="page-heading">
        <x-page-title title="Edit {{ $title }} Settings" subtitle="" pageTitle="Edit Settings" />

        <section class="section">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.settings.providers.update') }}" method="GET">
                        @csrf

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="provider" class="form-label">Provider</label>
                                    <select name="provider" id="provider" onchange="getProviderData(this.value)" class="form-control select2" required>
                                        <option value="" selected disabled>Select Provider</option>
                                        @foreach ($providers as $provider)
                                            <option value="{{ $provider }}">{{ $provider }}</option>
                                        @endforeach
                                    </select>

                                    @error('provider')
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
        });

        function getProviderData(provider) {
            console.log(provider);
            
        }
    </script>
@endpush
