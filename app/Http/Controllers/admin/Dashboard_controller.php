<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App;
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


class Dashboard_controller extends Controller
{
    //
    private $logged_user;

    public function __construct()
    {
        $this->middleware('check_user:admin');
        $this->logged_user=Auth::admin_user();
    }

    public function index()
    {
        $this->logged_user;

        $result['users']=User_model::get()->whereIn('user_role',array(2,3))->toArray();
        $result['shops']=Shops_model::select('shops.*',DB::raw('ifnull(SUM(shop_transactions.amount),0) as shops_earn'))
                                    ->leftjoin('shop_transactions', 'shops.shop_id', '=', 'shop_transactions.shop_id')
                                    ->groupBy('shops.shop_id')->get()->toArray();                                    
                                   
        // $result['all_shops']=['Shop 1', 'Shop 2', 'Shop 3', 'Shop 4', 'Shop 5', 'Shop 6', 'Shop 7', 'Shop 8', 'Shop 9', 'Shop 10'];
        // $result['shops_earn']=[280, 120, 82, 73, 95, 140, 180, 60, 84, 150];       
      
        return view('admin/dashboard',$result);
    }

   
    public function logout(Request $request)
    {
        $request->session()->forget('admin_token');
        return redirect('admin');
    }

        
    function payment_details_by_session($request)
    {
        
        $stripe = new \Stripe\StripeClient(
            'sk_test_51LfGgJSCia5qiodpIeTGpYQTrWuFs8AMuCXRu3SvVF7SeyRNHn4eroQOhpUWUWCKbAIsFc3kuENWh0RBYdnYhnIn00YrHjImwW'
          );
        $payment_details=$stripe->checkout->sessions->retrieve(
            $request['session_id'],
            []
          );

          return $payment_details;
    }

    function prebuild_checkout_page()
    {
        require 'vendor/autoload.php';
        // This is your test secret API key.
        \Stripe\Stripe::setApiKey('sk_test_51LfGgJSCia5qiodpIeTGpYQTrWuFs8AMuCXRu3SvVF7SeyRNHn4eroQOhpUWUWCKbAIsFc3kuENWh0RBYdnYhnIn00YrHjImwW');

        header('Content-Type: application/json');

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