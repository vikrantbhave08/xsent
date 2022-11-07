<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Swagger\Auth_controller;
use App\Http\Controllers\Swagger\Parent_controller;
use App\Http\Controllers\Swagger\Login_controller;
use App\Http\Controllers\Swagger\App_controller;
use App\Http\Controllers\Swagger\Pay_controller;
use App\Http\Controllers\Swagger\Swagger_controller;
use App\Http\Controllers\Home_controller;
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



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::group([
    // 'prefix' => 'v1',
    // 'as' => 'api.',
    'middleware' => ['auth:api']
], function () {
    //lists all users
});

Route::get('allusers', [Swagger_controller::class, 'all_users']);   
Route::post('login_auth', [Swagger_controller::class, 'login_auth']);
Route::post('register_auth', [Swagger_controller::class, 'register_auth']);
