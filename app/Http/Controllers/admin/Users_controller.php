<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User_model;
use App\Models\Auth_users;

class Users_controller extends Controller
{
    //

    public function __construct()
    {
        $this->middleware('check_user:admin');
    }

    public function index(Request $request)
    {
        $result['users']=User_model::select('users.*','auth_user.auth_id','user_roles.role_name')
                        ->leftjoin('user_roles', 'users.user_role', '=', 'user_roles.role_id')
                        ->leftjoin('auth_user', 'users.user_id', '=', 'auth_user.user_id')
                        ->where(function ($query) use ($request) {
                            if (!empty($request['email'])) $query->where('users.username', $request['email']);
                            if (!empty($request['user_role'])) $query->where('auth_user.user_role',$request['user_role']);
                            if (!empty($request['password'])) $query->where('users.password',sha1($request['password'].'appcart systemts pvt ltd'));
                        })
                        ->whereIn('users.user_role',array(2,3,4))->get()->toArray();

                                              
        return view('admin/register-user',$result);
    }

    public function register_user_details(Request $request)
    {
        $result['users']=User_model::select('users.*','auth_user.auth_id','user_roles.role_name')
                        ->leftjoin('user_roles', 'users.user_role', '=', 'user_roles.role_id')
                        ->leftjoin('auth_user', 'users.user_id', '=', 'auth_user.user_id')
                        ->where(function ($query) use ($request) {
                            if (!empty($request['email'])) $query->where('users.username', $request['email']);
                            if (!empty($request['user_role'])) $query->where('auth_user.user_role',$request['user_role']);
                            if (!empty($request['password'])) $query->where('users.password',sha1($request['password'].'appcart systemts pvt ltd'));
                        })
                        ->whereIn('users.user_role',array(2,3,4))->get()->toArray();

                                              
        return view('admin/register-user-details',$result);
    }


    public static function admin_user()
    {       
        $user = User_model::select('users.*','auth_user.auth_id','auth_user.users_token')
        ->leftjoin('auth_user', 'users.user_id', '=', 'auth_user.user_id')
        ->where('auth_user.users_token', session('admin_token'))
        ->first();
        if(!empty($user))
        {
            $user = $user->toArray();       
        }

        return $user;
    }
}
