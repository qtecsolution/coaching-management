<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\BatchController;
use App\Http\Controllers\Admin\ClassMaterialController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\LeadController;
use App\Http\Controllers\Admin\MessageController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\StudentBatchController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\User\PermissionController;
use App\Http\Controllers\Admin\User\RoleController;
use App\Http\Controllers\Admin\User\UserController;
use App\Http\Controllers\SmsController;
use App\Http\Controllers\Student\ClassMaterialController as StudentClassMaterialController;
use App\Http\Controllers\Student\DashboardController as StudentDashboardController;
use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Route;

// home page
Route::get('/', function () {
    return to_route('auth.login');
});

// auth routes
Route::prefix('auth')->as('auth.')->group(function () {
    Route::middleware('auth')->group(function () {
        // login
        Route::get('/login', [AuthController::class, 'loginView'])->name('login.show');
        Route::post('/login', [AuthController::class, 'login'])->name('login');

        // registration
        Route::get('/sign-up', [AuthController::class, 'signupView'])->name('signup.show');
        Route::post('/sign-up', [AuthController::class, 'signup'])->name('signup');

        // forgot password
        Route::get('/forgot-password', [AuthController::class, 'forgotPasswordView'])->name('forgot-password.show');
        Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('forgot-password');

        // reset password
        Route::get('/reset-password/{token}', [AuthController::class, 'resetPasswordView'])->name('reset-password.show');
        Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('reset-password');
    });

    // profile
    Route::get('/profile', [AuthController::class, 'profileView'])->name('profile');
    Route::post('/profile', [AuthController::class, 'updateProfile'])->name('profile.update');
    Route::post('/profile/avatar', [AuthController::class, 'updateAvatar'])->name('avatar.update');

    // logout
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
});

// admin routes
Route::prefix('admin')->as('admin.')->middleware('isAdmin')->group(function () {
    // dashboard
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    // user routes
    Route::resource('users', UserController::class);
    Route::as('users.')->group(function () {
        // Role resource routes
        Route::resource('roles', RoleController::class);

        // Additional route for updating permissions within roles
        Route::post('/roles/{role}/update-permissions', [RoleController::class, 'updatePermissions'])->name('roles.updatePermissions');

        // Permission resource routes
        Route::resource('permissions', PermissionController::class);
    });

    // setting routes
    Route::prefix('/settings')->as('settings.')->controller(SettingController::class)->group(function () {
        Route::get('/{type}/edit', 'edit')->name('edit');
        Route::post('/update', 'update')->name('update');

        Route::prefix('/sms/providers')->as('sms.')->group(function () {
            Route::get('/{name}', 'smsProvider');
            Route::post('/update', 'updateSmsProviders')->name('providers.update');
            Route::get('/test/{provider}', [SmsController::class, 'testSms']);
        });
    });

    // student routes
    Route::resource('students', StudentController::class);
    Route::get('/students/{id}', [StudentController::class, 'idCard'])->name('students.id');

    // course routes
    Route::resource('courses', CourseController::class);

    // batch routes
    Route::resource('batches', BatchController::class);
    Route::get('/batch/{id}/students', [StudentBatchController::class, 'index'])->name('batches.students');

    // lead routes
    Route::resource('leads', LeadController::class);

    // class material routes
    Route::resource('class-materials', ClassMaterialController::class);
    Route::post('/class-materials/get-days', [ClassMaterialController::class, 'getDays'])->name('class-materials.get-days');

    // message routes
    Route::resource('messages', MessageController::class);

    // payment routes
    Route::resource('payments', PaymentController::class);
    Route::post('/payments/get-info', [PaymentController::class, 'getInfo']);
});

// user routes
Route::prefix('user')->as('user.')->middleware('isStudent')->group(function () {
    // dashboard
    Route::get('/', [StudentDashboardController::class, 'index'])->name('dashboard');

    // class material
    Route::get('class-materials', [StudentClassMaterialController::class, 'index'])->name('class-materials.index');
});

// test routes
Route::get('/test', [TestController::class, 'index']);
