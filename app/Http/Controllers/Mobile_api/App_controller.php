<?php

namespace App\Http\Controllers\Mobile_api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Mobile_api\Login_controller;

use App\Models\User_model;
use App\Models\Auth_users;
use App\Models\Parent_child_model; 
use App\Models\Wallet_model;
use App\Models\Wallet_transaction_model;

class App_controller extends Controller
{
    
    public function __construct()
    {               
        $this->middleware('CheckApiToken:app');        
    }

    

    public function reset_password(Request $request)
    {   
       
        $data=array('status'=>false,'msg'=>'Data not found');
        
        if($request['token'] && $request['current_password'] && $request['new_password'])
        {                                           
                // $mobile_user=Auth::mobile_app_user($request['token']);
                     
                $auth_user=User_model::select('users.*','auth_user.auth_id')
                                    ->leftjoin('auth_user', 'users.user_id', '=', 'auth_user.user_id')
                                    ->where('auth_user.users_token',$request['token'])
                                    ->where('users.password',sha1($request['current_password'].'appcart systemts pvt ltd'))->first();               
               
                if(!empty($auth_user))
                {
                    $auth_user->password = sha1($request['new_password'].'appcart systemts pvt ltd');
                    $auth_user->passphrase = $request['new_password'];
                    $auth_user->updated_at= date('Y-m-d H:i:s');
                    $auth_user->save();
                    $data=array('status'=>true,'msg'=>'Password changed successfully');                                   
                } else {

                    $data=array('status'=>false,'msg'=>'Current password does not match');                                   
                }
            
         }

        
        echo json_encode($data);
    }
}
