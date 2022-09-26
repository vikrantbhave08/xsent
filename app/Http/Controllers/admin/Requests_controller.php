<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;

use App\Models\User_model;
use App\Models\Auth_users;
use App\Models\Parent_child_model; 
use App\Models\Wallet_model;
use App\Models\Wallet_transaction_model;
use App\Models\Shop_transaction_model;
use App\Models\Shops_model;
use App\Models\Shopkeepers_model;
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
        $result['requests']=Amount_requests_model::select('users.first_name','users.last_name','amount_requests.*','shops.shop_name','wallet.balance')
                                                ->leftjoin('users', 'amount_requests.by_user', '=', 'users.user_id') 
                                                ->leftjoin('wallet', 'users.user_id', '=', 'wallet.user_id') 
                                                ->leftjoin('shops', 'amount_requests.by_user', '=', 'shops.owner_id') 
                                                ->whereIn('wallet.user_role',array(2,3))
                                                // ->whereIn('amount_requests.by_role',array(2,3))
                                                // ->whereYear('amount_requests.created_at', '=', date('Y'))
                                                // ->whereMonth('amount_requests.created_at',"=",$i)
                                                ->get()->toArray();

                                                // $result['requests']=array();
                                                // echo "<pre>";
                                                // print_r($result);
                                                // exit;

        return view('admin/request',$result);
    }

    public function get_payment_details(Request $request)
    {        
        $result=array('status'=>false,'msg'=>'Data not found');

        if($request['request_id'])
        {

            $result['requests']=Amount_requests_model::select('users.first_name','users.last_name','amount_requests.*','shops.shop_name','wallet.balance')
            ->leftjoin('users', 'amount_requests.by_user', '=', 'users.user_id') 
            ->leftjoin('wallet', 'users.user_id', '=', 'wallet.user_id') 
            ->leftjoin('shops', 'amount_requests.by_user', '=', 'shops.owner_id') 
            ->where('amount_requests.amt_request_id',$request['request_id'])
         
            ->get()->toArray();

            $payment_details=array();
            $result=array('status'=>true,'msg'=>'Data found','pay_details'=>$payment_details);
        }

        echo json_encode($result);
    }
}
