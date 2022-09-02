<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class Dashboard_controller extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('check_user:admin');
    }

    public function index()
    {
        $user_data=Auth::admin_user();    
      
        return view('admin/dashboard');
    }

    public function logout(Request $request)
    {
        $request->session()->forget('admin_token');
        return redirect('admin');
    }
}
