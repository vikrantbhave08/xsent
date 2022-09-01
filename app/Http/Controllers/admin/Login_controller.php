<?php

namespace App\Http\Controllers\admin;

use Config;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User_model;
use App\Models\Auth_users;

class Login_controller extends Controller
{
    
    public function __construct()
    {
        $this->middleware('isuserloggedin:admin');
    }

    public function index()
    {       
        return view('admin/login',['user_role'=>1]);
    }

    
    public function loginme(Request $request)
    {
      
        $data=array('flag'=>false,'msg'=>'Data not found');

        if($request['username'] && $request['password'] && $request['user_role'])
         {
            $user = User_model::select('users.*','auth_user.auth_id')
                                ->leftjoin('auth_user', 'users.user_id', '=', 'auth_user.user_id')
                                ->where('users.email', $request['username'])
                                ->whereIn('users.user_role',array(1,6))
                                ->where('users.password',sha1($request['password'].'appcart systemts pvt ltd'))->first();

                                                            
            if(!empty($user))
            {
                $user = $user->toArray();
                if($user['user_role']==1)
                {
                  
                    $request->session()->put('admin_role', $user['user_role']);
                    $gen_token=sha1(mt_rand(11111,99999).date('Y-m-d H:i:s'));
                    if($user['auth_id']!='')
                    {                        
                        $auth_user = Auth_users::where('auth_id', $user['auth_id'])->first();                        
                        $auth_user->users_token=$gen_token;
                        $auth_user->updated_at= date('Y-m-d H:i:s');
                        $auth_user->save();

                    } else {

                        $create_auth_user = Auth_users::create([
                            'user_id' => $user['user_id'],
                            'user_role' => $user['user_role'],
                            'users_token' => $gen_token,
                            'created_at' =>  date('Y-m-d H:i:s'),
                            'updated_at' =>  date('Y-m-d H:i:s')
                        ])->auth_id;

                    }

                    $request->session()->put('admin_token', $gen_token);
                }
                $data=array('flag'=>true,'msg'=>'Login Successful.');
            } else {
                $data=array('flag'=>false,'msg'=>'Invalid Credentials');
            }
         }
         return json_encode($data);
    }
    
}
