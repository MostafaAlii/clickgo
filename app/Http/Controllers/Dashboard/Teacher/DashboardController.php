<?php
namespace App\Http\Controllers\Dashboard\Teacher;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
class DashboardController extends Controller {
    public function __invoke() {
        return view('dashboard.Teacher.dashboard', ['PageTitle' => trans('dashboard/header.main_dashboard')]);
    }
}
