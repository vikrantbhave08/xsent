<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Mobile_api\Parent_controller;
use App\Http\Controllers\Mobile_api\Login_controller;
use App\Http\Controllers\Mobile_api\App_controller;
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

Route::group(['middleware' => ['api']], function () {
    // Route::get('admin1', [Login_controller::class, 'index']);
    // your routes here
});
Route::post('register', [Login_controller::class, 'register']);
Route::post('login', [Login_controller::class, 'login']);
Route::post('logout', [Login_controller::class, 'logout']);
Route::post('send-otp', [Login_controller::class, 'send_otp']);
Route::post('verify-mobile', [Login_controller::class, 'verify_mobile']);
Route::post('forgot-password', [Login_controller::class, 'forgot_password']);

Route::post('reset-password', [App_controller::class, 'reset_password']);

Route::post('get-users-profile', [Parent_controller::class, 'get_users_profile']);
Route::post('add-child', [Parent_controller::class, 'add_child']);
Route::Post('getall-children', [Parent_controller::class, 'getall_children']); 
Route::Post('get-child-details', [Parent_controller::class, 'get_child_details']); 

Route::Post('add-money-to-wallet', [Parent_controller::class, 'add_money_to_wallet']); 

