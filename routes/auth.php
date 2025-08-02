<?php

use App\Http\Controllers\Auth\{Admin, Teacher, Academic};
use Illuminate\Support\Facades\Route;
Route::middleware('guest')->group(function () {
    Route::prefix('admin')->group(function () {
        Route::get('login', [Admin\AdminAuthenticatedSessionController::class, 'create'])->name('admin.login');
        Route::post('login', [Admin\AdminAuthenticatedSessionController::class, 'store'])->name('admin.post.login');
        Route::get('forgot/password', [Admin\AdminAuthenticatedSessionController::class, 'forgot_password'])->name('admin.forgot.password');
        Route::post('forgot/password', [Admin\AdminAuthenticatedSessionController::class, 'forgot_password_post'])->name('admin.post.forgot.password');
        Route::get('reset/password/{token}', [Admin\AdminAuthenticatedSessionController::class, 'reset_password'])->name('admin.reset.password');
        Route::post('reset/password/{token}', [Admin\AdminAuthenticatedSessionController::class, 'do_reset_password'])->name('admin.do.reset.password');
    });

    Route::prefix('teacher')->group(function () {
        Route::get('login', [Teacher\TeacherAuthenticatedSessionController::class, 'create'])->name('teacher.login');
        Route::post('login', [Teacher\TeacherAuthenticatedSessionController::class, 'store'])->name('teacher.post.login');
        Route::get('forgot/password', [Teacher\TeacherAuthenticatedSessionController::class, 'forgot_password'])->name('teacher.forgot.password');
        Route::post('forgot/password', [Teacher\TeacherAuthenticatedSessionController::class, 'forgot_password_post'])->name('teacher.post.forgot.password');
        Route::get('reset/password/{token}', [Teacher\TeacherAuthenticatedSessionController::class, 'reset_password'])->name('teacher.reset.password');
        Route::post('reset/password/{token}', [Teacher\TeacherAuthenticatedSessionController::class, 'do_reset_password'])->name('admin.do.reset.password');
    });

    Route::prefix('academic')->as('academic.')->group(function () {
        Route::get('login', [Academic\AcademicAuthenticatedSessionController::class, 'create'])->name('login');
        Route::post('login', [Academic\AcademicAuthenticatedSessionController::class, 'store'])->name('post.login');
        Route::get('forgot/password', [Academic\AcademicAuthenticatedSessionController::class, 'forgot_password'])->name('forgot.password');
        Route::post('forgot/password', [Academic\AcademicAuthenticatedSessionController::class, 'forgot_password_post'])->name('post.forgot.password');
        Route::get('reset/password/{token}', [Academic\AcademicAuthenticatedSessionController::class, 'reset_password'])->name('reset.password');
        Route::post('reset/password/{token}', [Academic\AcademicAuthenticatedSessionController::class, 'do_reset_password'])->name('do.reset.password');
    });
});

Route::middleware('auth:admin')->group(function () {
    Route::prefix('admin')->group(function () {
        Route::post('logout', [Admin\AdminAuthenticatedSessionController::class, 'destroy'])->name('admin.logout');
    });
});

Route::middleware('auth:teacher')->group(function () {
    Route::prefix('teacher')->group(function () {
        Route::post('logout', [Teacher\TeacherAuthenticatedSessionController::class, 'destroy'])->name('teacher.logout');
    });
});

Route::middleware('auth:academic')->group(function () {
    Route::prefix('academic')->as('academic.')->group(function () {
        Route::post('logout', [Academic\AcademicAuthenticatedSessionController::class, 'destroy'])->name('logout');
    });
});

