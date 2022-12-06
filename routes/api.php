<?php

use App\Http\Controllers\API\DashboardController;
use App\Http\Controllers\API\LoginController;
use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\API\VerifyMobileController;
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


Route::controller(RegisterController::class)->group(function(){
    Route::post('register', 'register');
});
Route::controller(LoginController::class)->group(function(){
    Route::post('login', 'login');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('verify-mobile', [VerifyMobileController::class, '__invoke'])
    ->middleware(['throttle:6,1']);
});


Route::middleware(['auth:sanctum' , 'verify.mobile'])->group(function () {
    Route::resource('tags', TagController::class);

    Route::controller(PostController::class)->group(function () {
        Route::delete('posts/{id}/restore','restore');
    });
    Route::resource('posts', PostController::class);

    Route::get('stats',[DashboardController::class, 'stats']);
});



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
// ->middleware(['auth', 'verify.mobile'])
