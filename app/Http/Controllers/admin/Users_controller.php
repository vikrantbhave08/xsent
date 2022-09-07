<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
                        ->whereIn('users.user_role',array(2,3,4))->get()->toArray();

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
        $result['users']=User_model::select('users.*','auth_user.auth_id','user_roles.role_name')
                        ->leftjoin('user_roles', 'users.user_role', '=', 'user_roles.role_id')
                        ->leftjoin('auth_user', 'users.user_id', '=', 'auth_user.user_id')
                        ->where(function ($query) use ($request) {
                            if (!empty($request['email'])) $query->where('users.username', $request['email']);
                            if (!empty($request['user_role'])) $query->where('auth_user.user_role',$request['user_role']);
                            if (!empty($request['password'])) $query->where('users.password',sha1($request['password'].'appcart systemts pvt ltd'));
                        })
                        ->whereIn('users.user_role',array(2,3,4))->get()->toArray();

                                              
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

    public function payment()
    {
        require 'vendor/autoload.php';
        // This is your test secret API key.
        \Stripe\Stripe::setApiKey('sk_test_51LfGgJSCia5qiodpIeTGpYQTrWuFs8AMuCXRu3SvVF7SeyRNHn4eroQOhpUWUWCKbAIsFc3kuENWh0RBYdnYhnIn00YrHjImwW');

        header('Content-Type: application/json');

        $YOUR_DOMAIN = 'http://localhost:4242/public';

        $stripe = new \Stripe\StripeClient('sk_test_51LfGgJSCia5qiodpIeTGpYQTrWuFs8AMuCXRu3SvVF7SeyRNHn4eroQOhpUWUWCKbAIsFc3kuENWh0RBYdnYhnIn00YrHjImwW');

        $PRODUCT_ID=$stripe->products->create(
        [
            'name' => 'Basic Dashboard',
            'default_price_data' => [
            'unit_amount' => 100.0,
            'currency' => 'INR',
            // 'recurring' => ['interval' => 'month'],
            ],
            'expand' => ['default_price'],
        ]
        );

       

        $PRICE_ID=$stripe->prices->create(
            [
              'product' => $PRODUCT_ID->id,
              'unit_amount' => 100.0,
              'currency' => 'INR',
            //   'recurring' => ['interval' => 'month'],
            ]
          );

        //   $checkout_session = \Stripe\Checkout\Session::create([
        //     'line_items' => [[
        //       # Provide the exact Price ID (e.g. pr_1234) of the product you want to sell
        //       'price' => $PRICE_ID,
        //       'quantity' => 1,
        //     ]],
        //     'mode' => 'payment',
        //     'success_url' => $YOUR_DOMAIN . '/success.html',
        //     'cancel_url' => $YOUR_DOMAIN . '/cancel.html',
        //   ]);


        // echo $PRICE_ID;
        // exit;
      

          $checkout_session=$stripe->checkout->sessions->create([
            'success_url' => 'https://example.com/success',
            'cancel_url' => 'https://example.com/cancel',
            'line_items' => [
              [
                'price' => $PRICE_ID->id,
                'quantity' => 1,
              ],
            ],
            'mode' => 'payment',
          ]);

          echo $checkout_session;
          exit;

        header("HTTP/1.1 303 See Other");
        header("Location: " . $checkout_session->url);
    }
}
