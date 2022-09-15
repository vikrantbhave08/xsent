<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Amount_requests_model;

class Requests_controller extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('check_user:admin');
    }

    public function index()
    {

        $result['requests']=Amount_requests_model::select('users.first_name','users.last_name','amount_requests.*')
                                                ->leftjoin('users', 'amount_requests.by_user', '=', 'users.user_id') 
                                                // ->where('amount_requests.by_user',$request['user_id'])
                                                // ->whereYear('amount_requests.created_at', '=', date('Y'))
                                                // ->whereMonth('amount_requests.created_at',"=",$i)
                                                ->get()->toArray();

                                                // echo "<pre>";
                                                // print_r($result);
                                                // exit;

        return view('admin/request',$result);
    }
}
