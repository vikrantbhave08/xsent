<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\api\App_controller;
// use App\Http\Controllers\api\Auth_controller;
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

Route::get('/greeting', function () {
    return 'Hello World';
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Route::group(['middleware' => ['web']], function () {
//     Route::get('admin1', [Login_controller::class, 'index']);
//     // your routes here
// })
;
Route::post('register', [Auth_controller::class, 'register']);
Route::post('login', [Auth_controller::class, 'login']);
Route::post('logout', [Auth_controller::class, 'logout']);
Route::post('send-otp', [Auth_controller::class, 'send_otp']);
Route::post('verify-mobile', [Auth_controller::class, 'verify_mobile']);
Route::post('forgot-password', [Auth_controller::class, 'forgot_password']);

Route::post('reset-password', [App_controller::class, 'reset_password']);
Route::post('get_data', [App_controller::class, 'get_data']);
Route::post('add-child', [App_controller::class, 'add_child']);
Route::Post('getall-children', [App_controller::class, 'getall_children']); 

Route::Post('add-money-to-wallet', [App_controller::class, 'add_money_to_wallet']); 

