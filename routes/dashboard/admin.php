<?php

use App\Http\Controllers\Dashboard;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
/*
|--------------------------------------------------------------------------
| Dashboard Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::group(
    [
        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => [ 'localeSessionRedirect', 'localizationRedirect', 'localeViewPath']
    ], function(){
        Route::group(['middleware' => 'auth:admin', 'prefix' => 'admin', 'as' => 'admin.'], function () {
            Route::get('dashboard', Dashboard\DashboardController::class)->name('dashboard');
            Route::resource('admins', Dashboard\AdminController::class);
            Route::resource('country', Dashboard\CountryController::class);
            Route::resource('categories', Dashboard\CategoryController::class);
            Route::resource('services', Dashboard\ServiceController::class);
            Route::delete('services/{id}/gallery/{image}', [Dashboard\ServiceController::class, 'deleteGallery'])->name('services.gallery.delete');
            Route::post('services/{id}/gallery/{image}', [Dashboard\ServiceController::class, 'updateGallery'])->name('services.gallery.update');
            Route::resource('professions', Dashboard\ProfessionController::class);
            Route::resource('documents', Dashboard\DocumentController::class);
            Route::resource('providers', Dashboard\ProviderController::class);
            Route::get('providers/{provider}', [Dashboard\ProviderController::class, 'show'])->name('providers.show');
            Route::put('provider-documents/{status}/status', [Dashboard\ProviderController::class, 'updateStatus'])->name('provider-documents.updateStatus');
            Route::post('providers/{provider}/approve-all-documents', [Dashboard\ProviderController::class, 'approveAllDocuments'])->name('providers.approveAllDocuments');
            Route::post('provider-documents/{status}/reupload', [Dashboard\ProviderController::class, 'reuploadDocument'])->name('provider-documents.reupload');
            Route::delete('providers/{id}/gallery/{image}', [Dashboard\ProviderController::class, 'deleteGallery'])->name('providers.gallery.delete');
            Route::post('providers/{id}/gallery/{image}', [Dashboard\ProviderController::class, 'updateGallery'])->name('providers.gallery.update');
            Route::post('providers/{id}/toggle-status', [Dashboard\ProviderController::class, 'toggleStatus'])->name('providers.toggleStatus');
        });
        require __DIR__.'../../auth.php';
});
