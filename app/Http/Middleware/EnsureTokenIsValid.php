<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureTokenIsValid
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next ,$role)
    {
           
        if($role=='admin')
        {
            if (!$request->session()->has('admin_token')) {
                return redirect('admin');
            } 
        }else
        if($role=='shop_owner')
        {
            if (!$request->session()->has('shop_owner_token')) {
                return redirect('shop_owner');
            }  
        }else
        if($role=='parent')
        {
            if (!$request->session()->has('parent_token')) {
                return redirect('psu');
            } 
        } else
        if($role=='student')
        {
            if (!$request->session()->has('student_token')) {
                return redirect('pu');
            }  
        } else
        if($role=='sap') 
        {
            if (!$request->session()->has('sap_token')) {
                return redirect('sap');
            }  
        }    
               
        return $next($request);
    }
}
