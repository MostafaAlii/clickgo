<?php
use Illuminate\Support\Facades\Route;
if(!function_exists('admin_guard')){
    function admin_guard() {
        return auth('admin');
    }
}

if(!function_exists('teacher_guard')){
    function teacher_guard() {
        return auth('teacher');
    }
}

if(!function_exists('academic_guard')){
    function academic_guard() {
        return auth('academic');
    }
}

if(!function_exists('check_guard')) {
    function check_guard() {
        $guards = ['admin', 'teacher', 'academic'];
        foreach($guards as $guard){
            if(auth($guard)->check()){
                return auth($guard);
            }
        }
        return null;
    }
}

if(!function_exists('get_user_data')) {
    function get_user_data() {
        $guards = ['admin', 'teacher', 'academic'];
        foreach($guards as $guard){
            if(auth($guard)->check()){
                return auth($guard)->user();
            }
        }
        return null;
    }
}

if (!function_exists('loadDashboardRoutes')) {
    function loadDashboardRoutes()
    {
        $dashboardPath = base_path('routes/dashboard');
        $files = glob($dashboardPath . '/*.php');

        foreach ($files as $file) {
            Route::middleware('web')->group($file);
        }
    }
}

