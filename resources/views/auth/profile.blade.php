@extends('layouts.master')

@section('title', 'Student List')

@push('css')
    <style>
        .avatar {
            position: relative;
        }

        .avatar-save-icon,
        .avatar-upload-icon {
            width: 30px;
            height: 30px;
            overflow: hidden;
            background: #dee2e6;
            border-radius: 50%;
            text-align: center;
            line-height: 30px;
            color: #000000;
            position: absolute;
            bottom: 0;
            right: 0;
            cursor: pointer !important;
        }

        .avatar-upload-icon input {
            position: absolute;
            display: block;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            opacity: 0;
            cursor: pointer !important;
        }

        .avatar-save-icon {
            right: -35px;
            border: none;
        }
    </style>
@endpush

@section('content')
    <div class="page-heading">
        <x-page-title title="Profile" subtitle="" pageTitle="Profile" />

        <section class="section">
            <div class="row">
                <div class="col-12 col-lg-8 order-2 order-lg-1">
                    <form action="{{ route('auth.profile.update') }}" method="post">
                        @csrf

                        <div class="card">
                            <div class="card-header">
                                <h4 class="cart-title">Change Details</h4>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="name" class="form-label">Name</label>
                                    <input type="text" name="name" id="name" class="form-control"
                                        placeholder="Your Name" value="{{ old('name', auth()->user()->name) }}">

                                    @error('name')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="phone" class="form-label">Phone</label>
                                    <input type="text" name="phone" id="phone" class="form-control"
                                        placeholder="Your Phone" value="{{ old('phone', auth()->user()->phone) }}">

                                    @error('phone')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="text" name="email" id="email" class="form-control"
                                        placeholder="Your Email" value="{{ old('email', auth()->user()->email) }}">

                                    @error('email')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <h4 class="cart-title">Change Password</h4>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="text" name="password" id="password" class="form-control"
                                        placeholder="Your Password" value="{{ old('password') }}">

                                    @error('password')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                                    <input type="text" name="password_confirmation" id="password_confirmation"
                                        class="form-control" placeholder="Your Confirm Password"
                                        value="{{ old('password_confirmation') }}">

                                    @error('password_confirmation')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-12 col-lg-4 order-1 order-lg-2">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-center align-items-center flex-column">
                                <form action="{{ route('auth.avatar.update') }}" method="post"
                                    enctype="multipart/form-data" class="avatar avatar-2xl">
                                    @csrf

                                    @php
                                        if (auth()->user()->avatar) {
                                            $avatar = asset('storage/' . auth()->user()->avatar);
                                        } else {
                                            $avatar = asset('assets/static/images/faces/2.jpg');
                                        }
                                    @endphp

                                    <img src="{{ $avatar }}"
                                        alt="{{ auth()->user()->name }}" id="profile-photo">

                                    <div class="avatar-upload-icon">
                                        <i class="bi bi-image"></i>
                                        <input type="file" name="avatar"
                                            onchange="photoPreview(event, 'profile-photo')" id="avatar-input">
                                    </div>
                                    <button type="submit" class="avatar-save-icon d-none">
                                        <i class="bi bi-check"></i>
                                    </button>
                                </form>

                                <h3 class="mt-3">{{ auth()->user()->name }}</h3>
                                <p class="badge bg-primary">
                                    @if (auth()->user()->user_type == 'admin')
                                        Admin User
                                    @elseif (auth()->user()->user_type == 'teacher')
                                        #{{ auth()->user()->teacher->teacher_id }}
                                    @elseif (auth()->user()->user_type == 'student')
                                        #{{ auth()->user()->student->reg_id }}
                                    @else
                                        {{ auth()->user()->phone }}
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const avatarInput = document.getElementById('avatar-input');
            const avatarSaveIcon = document.querySelector('.avatar-save-icon');

            avatarInput.addEventListener('change', function() {
                if (avatarInput.files && avatarInput.files.length > 0) {
                    avatarSaveIcon.classList.remove('d-none');
                } else {
                    avatarSaveIcon.classList.add('d-none');
                }
            });
        });
    </script>
@endpush
