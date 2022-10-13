<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Complaints_model;

class Complaints_controller extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('check_user:admin');
    }

    public function index()
    {         
        $result['user_role']=1;
        $result['complaints']=Complaints_model::select('complaints.*')->where('is_active',1)->get()->toArray();
        
        return view('admin/complaints',$result);
    }

    public function complaint_details()
    {
        return view('admin/complaint-details',['user_role'=>1]);
    }
}
