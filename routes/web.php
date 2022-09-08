<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Home_controller;

use App\Http\Controllers\admin\Login_controller;
use App\Http\Controllers\admin\Dashboard_controller;
use App\Http\Controllers\admin\Users_controller;
use App\Http\Controllers\admin\Complaints_controller;
use App\Http\Controllers\admin\Requests_controller;

use App\Http\Resources\api\App_resource;

// use App\Http\Controllers\psu\Login_controller as Psu_login;
// use App\Http\Controllers\psu\Dashboard_controller as Psu_dashboard;
// use App\Http\Controllers\psu\User_controller as Psu_user;
// use App\Http\Controllers\psu\Assessment_controller as Psu_assessment;

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

// Route::get('/', function () {
//     return view('welcome');
// });


Route::get('/', [Home_controller::class, 'index']);

Route::get('admin', [Login_controller::class, 'index']);
Route::get('admin/logout', [Dashboard_controller::class, 'logout']);
Route::post('admin/loginme', [Login_controller::class, 'loginme']);
Route::get('admin/dashboard', [Dashboard_controller::class, 'index']);
Route::get('admin/complaints', [Complaints_controller::class, 'index']);
Route::get('admin/complaint-details', [Complaints_controller::class, 'complaint_details']);
Route::get('admin/requests', [Requests_controller::class, 'index']);
Route::get('admin/register-users', [Users_controller::class, 'index']);

Route::post('admin/payment_details', [Users_controller::class, 'payment_details']);
Route::post('admin/payment', [Users_controller::class, 'prebuild_checkout_page']);
Route::get('admin/payment-status', [Users_controller::class, 'payment_status']);

Route::get('admin/register-user-details', [Users_controller::class, 'register_user_details']);