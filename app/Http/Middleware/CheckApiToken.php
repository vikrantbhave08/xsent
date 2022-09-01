<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

use App\Models\User_model;
use App\Models\Auth_users;


class CheckApiToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next,$role)
    {

        $request_data=$request->all();         
      if(!empty($request_data['token']))
      {
        $user = User_model::select('users.*','auth_user.auth_id','auth_user.users_token')
        ->leftjoin('auth_user', 'users.user_id', '=', 'auth_user.user_id')
        ->where('auth_user.users_token', $request_data['token'])
        ->first();

        if(empty($user))
        {
            return response()->json(['status'=>false,'msg'=>'Please Login First']);
        }
      } else {

            return response()->json(['status'=>false,'msg'=>'Please Login First']);

      }
        
      
        return $next($request);
    }
}
