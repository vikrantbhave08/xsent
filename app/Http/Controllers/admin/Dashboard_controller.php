<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App;


class Dashboard_controller extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('check_user:admin');
    }

    public function index()
    {
        $user_data=Auth::admin_user();    
      
        return view('admin/dashboard');
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