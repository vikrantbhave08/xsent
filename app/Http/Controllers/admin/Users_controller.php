<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\admin\Dashboard_controller;
use Illuminate\Http\Request;
use App;
use DB;

use App\Models\User_model;
use App\Models\Auth_users;
use App\Models\Parent_child_model; 
use App\Models\Wallet_model;
use App\Models\Wallet_transaction_model;
use App\Models\Payment_history_model;

class Users_controller extends Controller
{
    //

    public function __construct()
    {
        $this->middleware('check_user:admin');
    }

    public function index(Request $request)
    {

        $result['search_date']=!empty($request['search_date']) ? $request['search_date'] :'';
       
        $result['users']=User_model::select('users.*','auth_user.auth_id','user_roles.role_name','wallet.balance')
                        ->leftjoin('user_roles', 'users.user_role', '=', 'user_roles.role_id')
                        ->leftjoin('auth_user', 'users.user_id', '=', 'auth_user.user_id')
                        ->leftjoin('wallet', 'users.user_id', '=', 'wallet.user_id')
                        ->whereIn('users.user_role',array(2,3))
                        ->where(function ($query) use ($request) {
                            if (!empty($request['email'])) $query->where('users.username', $request['email']);
                            if (!empty($request['user_role'])) $query->where('auth_user.user_role',$request['user_role']);
                            if (!empty($request['password'])) $query->where('users.password',sha1($request['password'].'appcart systemts pvt ltd'));
                            if (!empty($request['search_date'])) $query->whereDate('users.created_at',$request['search_date']);
                        })
                        ->groupBy('users.user_id')->get()->toArray();
                        // ->where('users.user_role',"!=",1)->get()->toArray();

        foreach($result['users'] as $ukey=>$user)
        {
            $result['users'][$ukey]=$user;

            $parent_data=Parent_child_model::select('users.*','parent_child.child_id')
                                ->leftjoin('users', 'parent_child.parent_id', '=', 'users.user_id')
                                ->where('parent_child.child_id', $user['user_id'])
                                ->first();

                                if(!empty($parent_data) && $user['user_role']==4)
                                {
                                    $result['users'][$ukey]['parent_name']=$parent_data->first_name.' '.$parent_data->last_name;
                                    $result['users'][$ukey]['parent_email']=$parent_data->email;
                                } else {
                                    $result['users'][$ukey]['parent_name']='';
                                    $result['users'][$ukey]['parent_email']='';
                                }
        }
      
        return view('admin/register-user',$result);
    }

    public function register_user_details(Request $request)
    {
        $user_id=base64_decode($request['uid']);
       
        $user_details=User_model::where('users.user_id',$user_id)->first()->toArray();  

        $result['shop_user_details'] = $result['parent_user_details'] = $user_details;
         
        
        $as_a_shop=Wallet_model::where('user_id',$user_id)->where('user_role',2)->first();
        $result['shop_user_details']['wallet_balance'] = !empty($as_a_shop) ? $as_a_shop->balance : 0 ;

        $as_a_parent=Wallet_model::where('user_id',$user_id)->where('user_role',3)->first();
        $result['parent_user_details']['wallet_balance'] = !empty($as_a_parent) ? $as_a_parent->balance : 0 ;
      


                                    $transaction=array();
        for($user_role=2; $user_role<=3; $user_role++) //  get first for shop (role=2) and then parent (role=3)
        {
            $transactions=array();
            for($month=1; $month<=12; $month++)
            {
                for($i=0; $i<2; $i++) // for two status  bank transfer to user  & user transfer to bank account
                {

                    $trans=Payment_history_model::from('payment_history as ph')->select('ph.*')                                                
                                            ->where(function ($query) use ($request,$i,$user_role,$user_id) {                                                    
                                                                                    
                                                if($i==0) $query->where('ph.from_user', 0);         //bank transfer to user
                                                if($i==0) $query->where('ph.to_user',$user_id);     //bank transfer to user
                                                // if($i==0) $query->where('wt.from_user', 8);         //bank transfer to user
                                                // if($i==0) $query->where('wt.user_id',23);     //bank transfer to user
                                                if($i==0) $query->where('ph.to_role',$user_role);     //for user role 
                                                
                                                if($i==1) $query->where('ph.from_user', $user_id);         //user transfer to bank account
                                                if($i==1) $query->where('ph.from_role',$user_role);     //for user role 
                                                if($i==1) $query->where('ph.to_user', 0);     //user transfer to bank account
                                                // if($i==1) $query->where('wt.from_user', 6);         //user transfer to bank account
                                                // if($i==1) $query->where('wt.user_id', 8);     //user transfer to bank account
                                                                                                        
                                            })
                                            ->whereMonth('ph.created_at',"=",$month) 
                                            ->get()->toArray();
                    
        // $trans=Wallet_transaction_model::from('wallet_transaction as wt')->select('wt.*')
        //                                                 ->leftjoin('wallet', 'wt.user_id', '=', 'wallet.user_id')
        //                                                 ->leftjoin('users', 'wt.user_id', '=', 'users.user_id')                                                   
        //                                                 ->where(function ($query) use ($request,$i,$user_role,$user_id) {                                                    
                                                                                                
        //                                                     if($i==0) $query->where('wt.from_user', 0);         //bank transfer to user
        //                                                     if($i==0) $query->where('wt.user_id',$user_id);     //bank transfer to user
        //                                                     // if($i==0) $query->where('wt.from_user', 8);         //bank transfer to user
        //                                                     // if($i==0) $query->where('wt.user_id',23);     //bank transfer to user
        //                                                     if($i==0) $query->where('wt.to_role',$user_role);     //for user role 
                                                            
        //                                                     if($i==1) $query->where('wt.from_user', $user_id);         //user transfer to bank account
        //                                                     if($i==1) $query->where('wt.user_id', 0);     //user transfer to bank account
        //                                                     // if($i==1) $query->where('wt.from_user', 6);         //user transfer to bank account
        //                                                     // if($i==1) $query->where('wt.user_id', 8);     //user transfer to bank account
        //                                                     if($i==1) $query->where('wt.from_role',$user_role);     //for user role 
                                                                                                                      
        //                                                 })
        //                                                 ->whereMonth('wt.created_at',"=",$month) 
        //                                                 ->get()->toArray();

                                                        // echo "<pre>";
                                                        // print_r($trans);
                                                  
                                                        // [date('F', mktime(0,0,0,$month, 1, date('Y')))]
                                                        
                                   if(!empty($trans))
                                   {
                                        if(!empty($transactions[date('F', mktime(0,0,0,$month, 1, date('Y')))]))
                                        {
                                            $transactions[date('F', mktime(0,0,0,$month, 1, date('Y')))] = array_merge($transactions[date('F', mktime(0,0,0,$month, 1, date('Y')))], $trans);                
                                        } else {
                                            $transactions[date('F', mktime(0,0,0,$month, 1, date('Y')))] = $trans ;                
                                        }
                                    //   $transactions = array_merge($transactions, $trans);                
                                   }
                                  
                    }
                  
              }

            //   print_r($transactions);
            //   exit;

              
              $all_transactions=array();
              foreach($transactions as $key=>$val)
              {
                usort($val, function($a, $b) {
                    return strtotime($a['created_at']) - strtotime($b['created_at']);
                });

                $all_transactions[$key]=$val;                                
            }

           

           
             if($user_role==2) { $transaction['shop'] = $all_transactions ; } 
             if($user_role==3) { $transaction['parent'] = $all_transactions ; } 
        }

        $result['transaction']=$transaction;

        // User_model::select('users.*','wallet.balance as wallet_balance','wallet_transaction.credit','user_roles.role_name')
        //                             ->leftjoin('wallet', 'users.user_id', '=', 'wallet.user_id')
        //                             ->leftjoin('user_roles', 'users.user_role', '=', 'user_roles.role_id')
        //                             ->leftjoin('wallet_transaction', 'users.user_id', '=', 'wallet_transaction.user_id')
        //                             ->where('users.user_id',$user_id)->where('wallet.user_id',$user_id)
        //                             ->groupBy('wallet_transaction.credit')->first()->toArray();                        

        // if(!empty($user_details))
        // {
        //     $user_details->toArray();
        //     $auth_users=Auth_users::select('auth_user.*')
        //                             ->leftjoin('users', 'auth_user.user_id', '=', 'users.user_id')
        //                             ->where('users.user_id',$user_id)
        //                             ->groupBy('auth_user.user_id')->get()->toArray();
        // }                        
                                              
        return view('admin/register-user-details',$result);
    }


    public static function admin_user()
    {       
        $user = User_model::select('users.*','auth_user.auth_id','auth_user.users_token')
        ->leftjoin('auth_user', 'users.user_id', '=', 'auth_user.user_id')
        ->where('auth_user.users_token', session('admin_token'))
        ->first();
        if(!empty($user))
        {
            $user = $user->toArray();       
        }
        
        return $user; 
    }

    public function payment_status( Request $request)
    {      
        $payment_details=Dashboard_controller::payment_details_by_session($request->all());

        echo "<pre>";
        print_r($payment_details);
    }

    public function payment_details()
    {
            require 'vendor/autoload.php';

            // This is your test secret API key.
            \Stripe\Stripe::setApiKey('sk_test_51LfGgJSCia5qiodpIeTGpYQTrWuFs8AMuCXRu3SvVF7SeyRNHn4eroQOhpUWUWCKbAIsFc3kuENWh0RBYdnYhnIn00YrHjImwW');

          

            header('Content-Type: application/json');

            try {
                // retrieve JSON from POST body
                $jsonStr = file_get_contents('php://input');
                $jsonObj = json_decode($jsonStr);

                // Create a PaymentIntent with amount and currency
                $paymentIntent = \Stripe\PaymentIntent::create([
                    'amount' => 100,
                    'currency' => 'inr',
                    'automatic_payment_methods' => [
                        'enabled' => true,
                    ],
                ]);

                $output = [
                    'clientSecret' => $paymentIntent->client_secret,
                ];

                echo json_encode($output);
            } catch (Error $e) {
                http_response_code(500);
                echo json_encode(['error' => $e->getMessage()]);
            }
    }

    public function prebuild_checkout_page()
    {
       
        $checkout_session = Dashboard_controller::prebuild_checkout_page();       

        return redirect($checkout_session->url);
    }
}