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
use App\Models\Payment_history_model;

class Pay_controller extends Controller
{
    private $logged_user;

    public function __construct(Request $request)
    {               
        $this->middleware('CheckApiToken:app');          
    }
    
    public function webhook(Request $request)
    {
        file_put_contents('assets/myfile.json', json_encode($request->all()));
    }

    public function payment_details(Request $request)
    {
        $data=array('status'=>false,'msg'=>'Data not found');

        $logged_user=Auth::mobile_app_user($request['token']);
       
        $rules = [
            'payment_intent' => 'required',
            'amount' => 'required'
        ];    

        $customMessages = [
            'required' => 'The :attribute field is required.'
        ];
        $validator=Validator::make($request->all(),$rules,$customMessages);
    

        $validation_flag=true;
        if($validator->fails())
        {
            $messages=$validator->messages();
            $errors=$messages->all();
           
            $data['errors']=$errors;
            $validation_flag=false;

        } else {

            $txn_id="txn".md5(date('smdHyi').$logged_user['user_id'].mt_rand(1111,9999));

            $to_admin=array(
                                'pay_txn_id'=>$request['payment_intent'],
                                'from_user'=>$logged_user['user_id'],
                                'from_role'=>$logged_user['user_role'],
                                'to_user'=>0,
                                'to_role'=>0,
                                'amount'=>$request['amount'],
                                'created_at'=>date('Y-m-d H:i:s'),
                                'updated_at'=>date('Y-m-d H:i:s')
                           );
                            
            $payment_id=Payment_history_model::create($to_admin)->payment_id;

            if($payment_id)
            {

                $users_wallet_exists=Wallet_model::where('user_id',$logged_user['user_id'])->first();

                if(empty($users_wallet_exists))
                {

                    $created_wallet=Wallet_model::create([                        
                        'user_id' => $logged_user['user_id'],
                        'user_role' => $logged_user['user_role'],
                        'balance' => $request['amount'],
                        'max_limit_per_day' => '',
                        'max_limit_per_month' => '',
                        'low_balance_alert' => '',
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                        ])->wallet_id;

                } else {

                    $created_wallet=$users_wallet_exists->wallet_id;

                }               
              

                    Wallet_transaction_model::create([          
                        'txn_id'=>$txn_id,
                        'from_user' => 0,
                        'from_role' =>0,
                        'user_id' => $logged_user['user_id'],
                        'to_role' =>  $logged_user['user_role'],
                        'wallet_id' =>  $created_wallet,
                        'credit' => $request['amount'],
                        'debit' => '',
                        'payment_gate_id' => '',
                        'status_msg' => 'Admin added virtual money',
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                        ])->wallet_trans_id;    



                        $users_wallet_exists->balance=$users_wallet_exists->balance + $request['amount'];
                        $users_wallet_exists->updated_at=date('Y-m-d H:i:s');
                        $update=$users_wallet_exists->save();
                        
                        
                        $data=array('status'=>true,'msg'=>'Payment transfer and amount added into wallet');

            }
           
        }

        echo json_encode($data);

    }

    public function payment_intent(Request $request)
    {     

           $data=array('status'=>false,'msg'=>'Data not found','payment_intent'=>array());

            if($request['amount'])
            {
                $request['amount']=$request['amount']*100;

                \Stripe\Stripe::setApiKey(env("STRIPE_SECRETE"));

                header('Content-Type: application/json');
        
                $stripe = new \Stripe\StripeClient(env("STRIPE_SECRETE"));
                
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
