<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\TempController;
use App\Http\Controllers\StaticController;
use App\Http\Controllers\AjaxController;
use App\Http\Controllers\CalculationController;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//$this->group(['middleware' => 'Language'], function(){
//	Route::get('/', [StaticController::class, 'index']);
//	Route::get('/communities', [StaticController::class, 'communities']);
//	Route::get('/communities-compare', [StaticController::class, 'communities_compare']);
//	Route::get('/communities-compare-admin', [StaticController::class, 'communities_compare_admin']);
//	Route::get('/communities-compare/{year}', [StaticController::class, 'communities_compare']);
//	Route::get('/communities-compare-admin/{year}', [StaticController::class, 'communities_compare_admin']);
//	Route::get('/communities/{slug}', [StaticController::class, 'community']);
//	Route::get('/communities/{slug}/{year}', [StaticController::class, 'community']);
//	Route::get('/form', [StaticController::class, 'form']);
//	Route::get('/news', [StaticController::class, 'news']);
//	Route::get('/news/{slug}', [StaticController::class, 'news']);
//	Route::get('/{slug}', [StaticController::class, 'page']);
//});

Route::group(['prefix' => 'admin'], function(){
	Voyager::routes();
	Route::get('/import-csv/numbers/', [TempController::class, 'import_numbers']);
	Route::get('/import-csv/indicators/', [TempController::class, 'import_indicators']);
	Route::get('/import-data/', [TempController::class, 'import_data']);
	Route::get('/calculation/', [CalculationController::class, 'index']);
});

Route::group(['prefix' => LaravelLocalization::setLocale(), 'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localize']], function(){
	Route::get('/', [StaticController::class, 'index']);
	Route::get('/rating', [StaticController::class, 'index_rating']);
	Route::get('/methodology', [StaticController::class, 'index_methodology']);
	Route::get('/eea', [StaticController::class, 'index_eea']);
//Route::get('/communities', [StaticController::class, 'communities']);
	Route::get('/rating/communities', [StaticController::class, 'communities_rating']);
	Route::get('/rating/compare/', [StaticController::class, 'communities_compare']);
	Route::get('/rating/compare-admin/', [StaticController::class, 'communities_compare_admin']);
	Route::get('/rating/compare/{year}', [StaticController::class, 'communities_compare']);
	Route::get('/rating/compare-admin/{year}', [StaticController::class, 'communities_compare_admin']);
	Route::get('/rating/communities/{slug}', [StaticController::class, 'community']);
//Route::get('/communities/{slug}', [StaticController::class, 'community']);
	Route::get('/rating/communities/{slug}/{year}', [StaticController::class, 'community']);
//Route::get('/communities/{slug}/{year}', [StaticController::class, 'community']);
	Route::get('/register', [StaticController::class, 'form']);
	Route::get('/register-verify', [StaticController::class, 'register_verify']);
	Route::get('/contacts', [StaticController::class, 'contacts']);
	Route::get('/news', [StaticController::class, 'news']);
	Route::get('/news/{slug}', [StaticController::class, 'news']);
	Route::get('/{slug}', [StaticController::class, 'page']);
	Route::get('/{slug1}/{slug2}', [StaticController::class, 'page2']);
});

Route::get('/dashboard/admin/communities/{community_id}', function(){
	return File::get(public_path() . '/dashboard/admin/communities/index.html');
});

Route::get('/dashboard/admin/communities/{community_id}/{year}', function(){
	return File::get(public_path() . '/dashboard/admin/communities/index.html');
});

Route::get('/dashboard/admin/communities/{community_id}/{year}/files', function(){
	return File::get(public_path() . '/dashboard/admin/communities/index.html');
});

Route::get('/dashboard/admin/communities/{community_id}/{year}/metric', function(){
	return File::get(public_path() . '/dashboard/admin/communities/index.html');
});

Route::get('/dashboard/admin/rating/{year}', function(){
	return File::get(public_path() . '/dashboard/admin/rating/index.html');
});

Route::get('/dashboard/admin/data/indicators', function(){
	return File::get(public_path() . '/dashboard/admin/data/indicators/index.html');
});

Route::get('/dashboard/admin/data/measures', function(){
	return File::get(public_path() . '/dashboard/admin/data/measures/index.html');
});

Route::get('/dashboard/admin/data/sources', function(){
	return File::get(public_path() . '/dashboard/admin/data/sources/index.html');
});

Route::get('/dashboard/admin/users/active', function(){
	return File::get(public_path() . '/dashboard/admin/users/active/index.html');
});

Route::get('/dashboard/admin/users/blocked', function(){
	return File::get(public_path() . '/dashboard/admin/users/blocked/index.html');
});

Route::get('/dashboard/admin/users/requests', function(){
	return File::get(public_path() . '/dashboard/admin/users/requests/index.html');
});

Route::get('/dashboard/admin/users/unverified', function(){
	return File::get(public_path() . '/dashboard/admin/users/unverified/index.html');
});

Route::get('/dashboard/manager/rating/{year}', function(){
	return File::get(public_path() . '/dashboard/manager/rating/index.html');
});

Route::get('/dashboard/manager/rating/{year}/files', function(){
	return File::get(public_path() . '/dashboard/manager/rating/index.html');
});

Route::get('/dashboard/manager/rating/{year}/metric', function(){
	return File::get(public_path() . '/dashboard/manager/rating/index.html');
});

//ajax callbacks
Route::post('post/search-communities', [AjaxController::class, 'search_communities']);
Route::post('form-submit', [AjaxController::class, 'form_submit']);
Route::post('form-contact', [AjaxController::class, 'form_contact']);