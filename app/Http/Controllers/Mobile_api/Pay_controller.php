<?php

namespace App\Http\Controllers\Mobile_api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App;
use DB;
use Carbon\Carbon;
use Validator;


use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Mobile_api\Login_controller;

use App\Models\User_model;
use App\Models\Auth_users;
use App\Models\Parent_child_model; 
use App\Models\Wallet_model;
use App\Models\Wallet_transaction_model;
use App\Models\Shop_transaction_model;
use App\Models\Shops_model;
use App\Models\Shopkeepers_model;
use App\Models\Amount_requests_model;
use App\Models\Notifications_model;
use App\Models\Cities_model;
use App\Models\Province_model;
use App\Models\Bank_details_model;

class Pay_controller extends Controller
{
    private $logged_user;

    public function __construct(Request $request)
    {               
        $this->middleware('CheckApiToken:app');          
    }
    
    public function payment_intent(Request $request)
    {     

           $data=array('status'=>false,'msg'=>'Data not found','payment_intent'=>array());

            if($request['amount'])
            {
                $request['amount']=$request['amount']*100;

                \Stripe\Stripe::setApiKey(env("FOLOOSI_SECRETE"));

                header('Content-Type: application/json');
        
                $stripe = new \Stripe\StripeClient(env("FOLOOSI_SECRETE"));
                
                $payment_intent=$stripe->paymentIntents->create([
                    'amount' => $request['amount'],
                    'currency' => 'AED',
                    'payment_method_types' => ['card'],
                  ]);

                  $data=array('status'=>true,'msg'=>'Payment intent','payment_intent'=>$payment_intent);                  
            }

            echo json_encode($data);
    }

}
