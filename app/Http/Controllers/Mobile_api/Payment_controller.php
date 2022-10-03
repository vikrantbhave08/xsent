<?php


namespace App\Http\Controllers\Mobile_api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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

class App_controller extends Controller
{
    private $logged_user;

    public function __construct(Request $request)
    {               
        $this->middleware('CheckApiToken:app');     
        
        // $this->logged_user=Auth::mobile_app_user($request['token']);  
    }

    
    function prebuild_checkout()
    {       

            if($request['amount'])
            { 
                require 'vendor/autoload.php';
                // This is your test secret API key.
                \Stripe\Stripe::setApiKey(env("FOLOOSI_SECRETE"));

                header('Content-Type: application/json');

                $stripe = new \Stripe\StripeClient(env("FOLOOSI_SECRETE"));

                $PRODUCT_ID=$stripe->products->create(
                [
                    'name' => 'Basic Dashboard',
                    'default_price_data' => [
                    'unit_amount' => $request['amount']*100,
                    'currency' => 'INR',
                    // 'recurring' => ['interval' => 'month'],
                    ],
                    'expand' => ['default_price'],
                ]
                );
            

                $PRICE_ID=$stripe->prices->create(
                    [
                    'product' => $PRODUCT_ID->id,
                    'unit_amount' => $request['amount']*100,
                    'currency' => 'INR',
                    //   'recurring' => ['interval' => 'month'],
                    ]
                );      

                $checkout_session=$stripe->checkout->sessions->create([
                    'success_url' => App::make('url')->to('/admin/payment-status?session_id={CHECKOUT_SESSION_ID}'),
                    'cancel_url' =>  App::make('url')->to('/admin/payment-status?session_id={CHECKOUT_SESSION_ID}'),
                    'line_items' => [
                    [
                        'price' => $PRICE_ID->id,
                        'quantity' => 1,
                    ],
                    ],
                    'mode' => 'payment',
                ]);

            
                header("HTTP/1.1 303 See Other");
                // return Redirect::to($url);
                // header("Location: " . $checkout_session->url);
                return $checkout_session;
            }
    }

}
