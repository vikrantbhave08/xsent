<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// use App\Models\User_model;

class Home_controller extends Controller
{
    //

    public function __construct()
    {


    }

    public function index()
    {
        echo "home";
        // return view('homepage',['user_role'=>1]);
    }


}
