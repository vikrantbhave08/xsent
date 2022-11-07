<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckSwaggerBearer
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $token = $request->header('Authorization');

        if(!empty($token))
        {
            if(env("SWAGGER_BEARER")!=$token)
            {
                return response()->json(['status'=>false,'msg'=>'Invalid Authorazation key']);
            }     

        } else {
            
            return response()->json(['status'=>false,'msg'=>'Authorised first']);
        }
  
        
          return $next($request);
    }
}
