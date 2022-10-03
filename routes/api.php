<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ContentController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\ObjectController;
use App\Http\Controllers\API\CommunityController;
use App\Http\Controllers\API\MeasureValuesController;
use App\Http\Controllers\API\AdminController;
use App\Http\Controllers\API\DataController;
use App\Http\Controllers\API\FileController;
use App\Http\Controllers\API\ManagerController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('content-test', [ContentController::class, 'test']);

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->delete('logout', [AuthController::class, 'logout']);

Route::group(['prefix' => 'content', 'middleware' => 'auth:sanctum'], function(){
	Route::get('/', [ContentController::class, 'index']);
});

Route::group(['prefix' => 'sources', 'middleware' => 'auth:sanctum'], function(){
	Route::get('/', [ObjectController::class, 'get_sources']);
});

Route::group(['prefix' => 'numbers', 'middleware' => 'auth:sanctum'], function(){
	Route::get('/', [ObjectController::class, 'get_numbers']);
});

Route::group(['prefix' => 'groups', 'middleware' => 'auth:sanctum'], function(){
	Route::get('/', [ObjectController::class, 'get_groups']);
});

Route::group(['prefix' => 'sectors', 'middleware' => 'auth:sanctum'], function(){
	Route::get('/', [ObjectController::class, 'get_sectors']);
});

Route::group(['prefix' => 'admin/profile', 'middleware' => 'auth:sanctum'], function(){
	Route::get('/', [UserController::class, 'get_current_user']);
	Route::post('/avatar', [UserController::class, 'edit_avatar']);
});

Route::group(['prefix' => 'admin/communities', 'middleware' => 'auth:sanctum'], function(){
	Route::get('/', [CommunityController::class, 'get_communities']);
	Route::get('/{community_id}', [CommunityController::class, 'get_communities']);
	Route::get('/{community_id}/avatar', [CommunityController::class, 'get_community_avatar']);
	Route::get('/year/{year}', [CommunityController::class, 'get_year_communities']);
	Route::get('/{community_id}/{year}', [CommunityController::class, 'get_community_year']);
	Route::get('/{community_id}/{year}/files', [CommunityController::class, 'get_community_files']);
	Route::get('/{community_id}/{year}/metric', [CommunityController::class, 'get_community_metric']);
	Route::post('/{community_id}/avatar', [CommunityController::class, 'edit_community_avatar']);
	Route::post('/{community_id}', [CommunityController::class, 'edit_communities_id']);
	Route::post('/{community_id}/{year}/metric', [CommunityController::class, 'edit_community_metric']);
	Route::post('/', [CommunityController::class, 'edit_communities']);
});

Route::group(['prefix' => 'communities', 'middleware' => 'auth:sanctum'], function(){
	Route::get('/{community_id}', [CommunityController::class, 'get_community']);
	Route::post('/{community_id}', [CommunityController::class, 'edit_communities']);
	Route::post('/eea/{community_id}', [CommunityController::class, 'edit_eea_communities']);
	//Route::post('/total/{total_id}', [CommunityController::class, 'edit_total_communities']);
	Route::get('/users/{user_id}', [UserController::class, 'get_user_communities']);
	Route::post('/active/{community_id}', [CommunityController::class, 'active_community']);
	Route::post('/active/{community_id}/{year_id}', [CommunityController::class, 'active_community']);
	Route::post('/users/{user_id}', [UserController::class, 'edit_user_communities']);
	Route::delete('/users/{user_id}', [UserController::class, 'delete_user_communities']);
	Route::post('/year/{total_id}', [CommunityController::class, 'edit_total_communities']);
	Route::post('/year/{community_id}/{year_id}', [CommunityController::class, 'edit_year_communities']);
	Route::post('/{community_id}/avatar', [CommunityController::class, 'edit_community_avatar']);
});

Route::group(['prefix' => 'measure-values', 'middleware' => 'auth:sanctum'], function(){
	Route::post('/', [MeasureValuesController::class, 'edit_metrics']);
	Route::post('/{id}', [MeasureValuesController::class, 'edit_metrics_id']);
});

Route::group(['prefix' => 'admin/users', 'middleware' => 'auth:sanctum'], function(){
	Route::get('/{status}', [UserController::class, 'get_users']);
});

Route::group(['prefix' => 'admin/rating', 'middleware' => 'auth:sanctum'], function(){
	Route::get('/', [AdminController::class, 'get_rating']);
	Route::get('/{year}', [AdminController::class, 'get_rating']);
});

Route::group(['prefix' => 'admin/data', 'middleware' => 'auth:sanctum'], function(){
	Route::get('/indicators', [DataController::class, 'get_indicators']);
	Route::get('/measures', [DataController::class, 'get_measures']);
	Route::get('/sources', [DataController::class, 'get_sources']);
});

Route::group(['prefix' => 'admin/messages', 'middleware' => 'auth:sanctum'], function(){
	Route::get('/', [DataController::class, 'get_messages']);
	Route::get('/{status}', [DataController::class, 'get_messages']);
});

Route::group(['prefix' => 'manager', 'middleware' => 'auth:sanctum'], function(){
	Route::get('/profile', [UserController::class, 'get_current_user']);
	Route::get('/community', [ManagerController::class, 'get_manager_community']);
	Route::get('/community/{community_id}/avatar', [CommunityController::class, 'get_community_avatar']);
	Route::post('/community/{community_id}/avatar', [CommunityController::class, 'edit_community_avatar']);
	Route::get('/rating', [ManagerController::class, 'get_manager_rating']);
	Route::get('/rating/{year_id}', [ManagerController::class, 'get_manager_rating_year']);
	Route::get('/rating/{year_id}/metric', [ManagerController::class, 'get_manager_rating_year_metric']);
	Route::get('/rating/{year_id}/files', [ManagerController::class, 'get_manager_rating_year_files']);
	Route::post('/community/status', [ManagerController::class, 'edit_manager_community_status']);
});

Route::group(['prefix' => 'files', 'middleware' => 'auth:sanctum'], function(){
	Route::get('/form', [FileController::class, 'get_file_form']);
	Route::get('/type', [FileController::class, 'get_file_types']);
	Route::get('/source', [FileController::class, 'get_file_sources']);
	Route::get('/', [FileController::class, 'get_file_form']);
	Route::get('/{file_id}', [FileController::class, 'get_file_form']);
	Route::get('/form/{file_id}', [FileController::class, 'get_file_form']);
	Route::post('/link', [FileController::class, 'edit_file_form']);
	Route::post('/unlink', [FileController::class, 'edit_file_unlink']);
	Route::get('/type', [FileController::class, 'get_file_types']);
	Route::post('/type', [FileController::class, 'edit_file_types']);
	Route::get('/source', [FileController::class, 'get_file_sources']);
	Route::post('/source', [FileController::class, 'edit_file_sources']);
	Route::delete('/delete', [FileController::class, 'edit_file_delete']);
	Route::delete('/delete/{file_id}', [FileController::class, 'edit_file_delete']);
	Route::post('/', [FileController::class, 'edit_file_form']);
	Route::post('/{file_id}', [FileController::class, 'edit_file_form']);
});

Route::group(['prefix' => 'logs', 'middleware' => 'auth:sanctum'], function(){
	Route::get('/check', [AdminController::class, 'get_logs_check']);
});

Route::group(['prefix' => 'users', 'middleware' => 'auth:sanctum'], function(){
	Route::get('/', [UserController::class, 'get_current_user']);
	Route::post('/', [UserController::class, 'edit_current_user']);
	Route::post('/password', [UserController::class, 'edit_current_password']);
	Route::post('/avatar', [UserController::class, 'edit_avatar']);
});

Route::group(['prefix' => 'years', 'middleware' => 'auth:sanctum'], function(){
	Route::post('/{year_id}', [AdminController::class, 'edit_year']);
	Route::post('/{community_id}/{year_id}', [AdminController::class, 'edit_year']);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request){
	$user = $request->user();
	$user->avatar = Storage::url($user->avatar);
	return $request->user();
});