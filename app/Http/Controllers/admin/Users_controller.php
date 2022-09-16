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

class Users_controller extends Controller
{
    //

    public function __construct()
    {
        $this->middleware('check_user:admin');
    }

    public function index(Request $request)
    {
        $result['users']=User_model::select('users.*','auth_user.auth_id','user_roles.role_name','wallet.balance')
                        ->leftjoin('user_roles', 'users.user_role', '=', 'user_roles.role_id')
                        ->leftjoin('auth_user', 'users.user_id', '=', 'auth_user.user_id')
                        ->leftjoin('wallet', 'users.user_id', '=', 'wallet.user_id')
                        ->where(function ($query) use ($request) {
                            if (!empty($request['email'])) $query->where('users.username', $request['email']);
                            if (!empty($request['user_role'])) $query->where('auth_user.user_role',$request['user_role']);
                            if (!empty($request['password'])) $query->where('users.password',sha1($request['password'].'appcart systemts pvt ltd'));
                        })
                        ->where('users.user_role',"!=",1)->get()->toArray();

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

        // $user_details=User_model::where('users.user_id',$user_id)->first();
            

        $result['user_details']=User_model::select('users.*','wallet.balance as wallet_balance','wallet_transaction.credit','user_roles.role_name')
                                    ->leftjoin('wallet', 'users.user_id', '=', 'wallet.user_id')
                                    ->leftjoin('user_roles', 'users.user_role', '=', 'user_roles.role_id')
                                    ->leftjoin('wallet_transaction', 'users.user_id', '=', 'wallet_transaction.user_id')
                                    ->where('users.user_id',$user_id)->groupBy('wallet_transaction.credit')->first()->toArray();

                                 

        

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