<?php
namespace App\Http\Controllers\Dashboard\Academic;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
class DashboardController extends Controller {
    public function __invoke() {
        return view('dashboard.Academic.dashboard', ['PageTitle' => trans('dashboard/header.main_dashboard')]);
    }
}
