<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Metode;
use App\Models\Klasifikasi;
use App\Models\Unsur;
use App\Models\Penyelenggara;

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
Route::post('v1/register', [App\Http\Controllers\Api\AuthController::class, 'register']);
Route::middleware('auth:api')->group( function () {
    Route::resource('v1/users', App\Http\Controllers\Api\UserController::class);
    Route::resource('v1/roles', App\Http\Controllers\Api\RoleController::class);
    Route::resource('v1/permission', App\Http\Controllers\Api\PermissionController::class);
    Route::resource('v1/kegiatan', App\Http\Controllers\Api\KegiatanController::class);
    Route::get('v1/kegiatan/{kode}/peserta', [App\Http\Controllers\Api\KegiatanController::class,'peserta']);
    Route::post('v1/kegiatan/{kode}/peserta', [App\Http\Controllers\Api\KegiatanController::class,'peserta_add']);
    Route::post('v1/logout', [App\Http\Controllers\Api\AuthController::class, 'logout']);
});
Route::get('v1/unsur', [App\Http\Controllers\Api\UnsurController::class,'unsur']);
Route::get('v1/klasifikasi', function () {
        $data = Klasifikasi::select('id_bidang_profesi as kode','subklasifikasi as klasifikasi')->where('parent',0)->get();  
        return response()->json([
            'status'=>200,
            'message'=>'OK',
            'data' => $data], 200); 
});
Route::get('v1/metode', function () {
        $data = Metode::select('kode','metode')->get(); 
        return response()->json([
            'status'=>200,
            'message'=>'OK',
            'data' => $data], 200); 
});
Route::get('v1/penyelenggara', function () {
    $data = Penyelenggara::select('id as kode','penyelenggara')->get(); 
    return response()->json([
        'status'=>200,
        'message'=>'OK',
        'data' => $data], 200); 
});
Route::get('v1/cache', function () {
	Artisan::call('cache:forget spatie.permission.cache');
    Artisan::call('cache:clear');
    Artisan::call('optimize');
    //dd("Done"); php artisan optimize
});
Route::get('v1/swager', function () {
	Artisan::call('cl5-swagger:generate');
    //dd("Done"); php artisan optimize
});

