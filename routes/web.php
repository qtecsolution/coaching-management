<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\User\PermissionController;
use App\Http\Controllers\User\RoleController;
use App\Http\Controllers\User\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    // return view('welcome');
    return redirect()->route('auth.login');
});

// auth routes
Route::prefix('auth')->as('auth.')->group(function () {
    // login
    Route::get('/login', [AuthController::class, 'loginView'])->name('login.show');
    Route::post('/login', [AuthController::class, 'login'])->name('login');

    // registration
    Route::get('/sign-up', [AuthController::class, 'signupView'])->name('signup.show');
    Route::post('/sign-up', [AuthController::class, 'signup'])->name('signup');

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
});

// user routes
Route::prefix('user')->as('user.')->middleware('isStudent')->group(function () {
    // dashboard
    Route::get('/', [UserDashboardController::class, 'index'])->name('dashboard');
});
