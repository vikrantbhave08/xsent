<?php

namespace App\Http\Controllers\Mobile_api;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;


use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Mobile_api\Login_controller;

use App\Models\User_model;
use App\Models\Auth_users;
use App\Models\Parent_child_model; 
use App\Models\Wallet_model;
use App\Models\Wallet_transaction_model;
use App\Models\Shop_transaction_model;
use App\Models\Shops_model;
use App\Models\Shopkeepers_model;

class Parent_controller extends Controller
{
    public function __construct()
    {               
        $this->middleware('CheckApiToken:app');        
    }   
   
}
