<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
Use Session;

class isuserloggedin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next,$role)
    {
        // echo $request->session()->has('pbm_token');
        // exit;

        if($role=='admin')
        {
            if ($request->session()->has('admin_token')) {
                return redirect('admin/dashboard');
            }
        }else
        if($role=='shop_owner')
        {
            if ($request->session()->has('shop_owner_token')) {
                return redirect('pbm/user-list');
            }
        }else
        if($role=='parent')
        {
            if ($request->session()->has('parent_token')) {
                return redirect('psu/assign-assessment');
            }
        }else
        if($role=='student'){
            if ($request->session()->has('student_token')) {
                return redirect('pu/assign-assessment');
            }
        }


        return $next($request);
    }
}
