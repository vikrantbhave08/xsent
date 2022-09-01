<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Complaints_controller extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('check_user:admin');
    }

    public function index()
    {
        return view('admin/complaints',['user_role'=>1]);
    }

    public function complaint_details()
    {
        return view('admin/complaint-details',['user_role'=>1]);
    }
}
