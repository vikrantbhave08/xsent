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
Route::post('mobile-verified', [Login_controller::class, 'mobile_verified']);
Route::post('forgot-password', [Login_controller::class, 'forgot_password']);
Route::get('send-notification', [Login_controller::class, 'send_notification']);

Route::post('reset-password', [App_controller::class, 'reset_password']);

Route::post('get-users-profile', [App_controller::class, 'get_users_profile']);
Route::post('add-user', [App_controller::class, 'add_user']);
Route::post('update-user', [App_controller::class, 'update_user']);
Route::post('delete-user', [App_controller::class, 'delete_user']);
Route::Post('get-children', [App_controller::class, 'get_children']); 
Route::Post('get-child-details', [App_controller::class, 'get_child_details']); 

Route::Post('add-shop', [App_controller::class, 'add_shop']); 
Route::Post('get-shops-by-owner', [App_controller::class, 'get_shops_by_owner']); 
Route::Post('transaction-summary', [App_controller::class, 'transaction_summary']); 

Route::Post('add-money-to-wallet', [App_controller::class, 'add_money_to_wallet']); 
Route::Post('get-notifications', [App_controller::class, 'get_notifications']); 
Route::Post('get-users-wallet', [App_controller::class, 'get_users_wallet']); 
Route::Post('update-users-wallet', [App_controller::class, 'update_users_wallet']); 

Route::Post('qr-code-generate', [App_controller::class, 'qr_code_generate']); 

Route::Post('add-request', [App_controller::class, 'add_request']); 
Route::Post('request-money-history', [App_controller::class, 'request_money_history']); 

Route::Post('topup-history', [App_controller::class, 'topup_history']); 

Route::get('getall-province', [Login_controller::class, 'getall_province']); 
Route::Post('getall-cities-by-province', [Login_controller::class, 'getall_cities_by_province']); 
Route::get('getall-shop-categories', [Login_controller::class, 'getall_shop_categories']); 
