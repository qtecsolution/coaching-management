@extends('layouts.master')

@php
    $title = Str::replace('-', ' ', Str::upper($type));
@endphp
@section('title', 'Edit ' . $title)

@section('content')
    <div class="page-heading">
        <x-page-title title="Edit {{ $title }}" subtitle="" pageTitle="Edit Settings" />

        <section class="section">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.settings.sms.providers.update') }}" method="POST">
                        @csrf

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="provider" class="form-label">Provider</label>
                                <select name="provider" id="provider" onchange="getProviderData(this.value)"
                                    class="select2" style="width: 100%" required>
                                    <option value="" selected disabled>Select Provider</option>
                                    @foreach ($providers as $provider)
                                        <option value="{{ $provider }}" {{ $provider == $activeProvider['name'] ? 'selected' : '' }}>{{ $provider }}</option>
                                    @endforeach
                                </select>

                                @error('provider')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row" id="provider-data"></div>
                        
                        <div class="col-12 text-end my-2">
                            <button type="button" class="btn btn-warning" onclick="testProvider()">Test</button>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>

                        <div class="col-12" id="test-response">
                            
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

            getProviderData('{{ $activeProvider['name'] }}');
        });

        function getProviderData(provider) {
            $.ajax({
                url: "/admin/settings/sms/providers/" + provider,
                type: "GET",
                success: function(response) {
                    $('#provider-data').empty();
                    const credentials = response.credentials || {}; // Default to an empty object if null
                    response.fields.forEach(function(item) {
                        const value = credentials[item] || ''; // Safely access credentials
                        const capitalizedItem = item.toUpperCase();
                        $('#provider-data').append(`
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="${item}" class="form-label">${capitalizedItem}</label>
                                    <input 
                                        type="text" 
                                        name="${item}" 
                                        value="${value}" 
                                        id="${item}" 
                                        placeholder="${capitalizedItem}" 
                                        class="form-control" 
                                        required
                                    >
                                </div>
                            </div>
                        `);
                    });
                },
                error: function(error) {
                    console.log(error);
                }
            });
        }

        function testProvider() {
            const provider = $('#provider').val();
            // console.log(provider);
            
            $.ajax({
                url: "/admin/settings/sms/providers/test/" + provider,
                type: "GET",
                success: function(response) {
                    // console.log(response);

                    $('#test-response').empty();
                    $('#test-response').append(`
                        <div class="alert alert-success" role="alert">
                            ${response}
                        </div>
                    `);
                },
                error: function(error) {
                    // console.error(error);

                    $('#test-response').empty();
                    $('#test-response').append(`
                        <div class="alert alert-danger" role="alert">
                            Something went wrong. Please check your credentials and try again.
                        </div>
                    `);
                }
            });
        }
    </script>
@endpush
