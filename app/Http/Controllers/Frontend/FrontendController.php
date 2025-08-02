<?php
namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
class FrontendController extends Controller {
    public function __invoke() {
        return view('frontend.home', ['pageTitle' => trans('site/site.home_page_title')]);
    }
}
