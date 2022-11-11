<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
Route::post('v1/login', [App\Http\Controllers\Api\AuthController::class, 'login']);
Route::middleware('auth:api')->group( function () {
    Route::resource('v1/users', App\Http\Controllers\Api\UserController::class);
    Route::resource('v1/roles', App\Http\Controllers\Api\RoleController::class);
    Route::resource('v1/permission', App\Http\Controllers\Api\PermissionController::class);
});

Route::get('v1/cache', function () {
	Artisan::call('cache:forget spatie.permission.cache');
    Artisan::call('cache:clear');
    //dd("Done");
});
