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
use App\Models\Shop_cat_model;
use App\Models\Payment_history_model;


class Dashboard_controller extends Controller
{
    //
    private $logged_user;

    public function __construct()
    {
        $this->middleware('check_user:admin');
        // $this->logged_user=Auth::admin_user();
    }

    public function index()
    {
        // $this->logged_user;

        $result['users']=User_model::get()->whereIn('user_role',array(2,3))->toArray();
        $result['paid_to_shop']=Payment_history_model::select('payment_history.*',DB::raw('ifnull(SUM(payment_history.amount),0) as shops_earn'))
                                                       ->where('payment_history.to_role',2)->get()->toArray();
        $result['recieved_from_parent']=Payment_history_model::select('payment_history.*',DB::raw('ifnull(SUM(payment_history.amount),0) as parent_pays'))
                                                       ->where('payment_history.from_role',3)->get()->toArray();
        $result['shops']=Shops_model::select('shops.*',DB::raw('ifnull(SUM(shop_transactions.amount),0) as shops_earn'))
                                    ->leftjoin('shop_transactions', 'shops.shop_id', '=', 'shop_transactions.shop_id')
                                    ->groupBy('shops.shop_id')->get()->toArray();        

        $result['admin_recieve']=Wallet_transaction_model::select(DB::raw('ifnull(SUM(wallet_transaction.credit),0) as admin_earn')) 
                                                           ->where('user_id',0)->where('from_role',2)->get()->toArray();                                                              
                                                        //    echo "<pre>";
                                                        //    print_r($result);
                                                        //    exit;

        $categories=Shop_cat_model::get()->toArray();

        $cat_sales_by_month=array();
        foreach($categories as $cat_key=>$cat)
        {            
            $shops_by_categories=array_column(Shops_model::select('shop_id')->where('shop_cat_id',$cat['shop_cat_id'])->get()->toArray(),'shop_id');
            // echo "<pre>";
            // echo $cat['shop_cat_name'];
            // print_r($shops_by_categories);            
            $shop_sales=array();
            for($i=1; $i<=12; $i++)
            {
                $shop_sales_per_month=Shop_transaction_model::select('shop_transactions.*',DB::raw('ifnull(SUM(shop_transactions.amount),0) as total_sale'))
                                                        // ->leftjoin('users', 'shop_transactions.by_user', '=', 'users.user_id')    
                                                        // ->leftjoin('shops', 'shop_transactions.shop_id', '=', 'shops.shop_id') 
                                                        // ->where(function ($query) use ($request,$logged_user) {
                                                        // if (!empty($request['shop_gen_id'])) $query->where('shops.shop_gen_id',$request['shop_gen_id']);  
                                                        // if (($logged_user['user_role']==3 || $logged_user['user_role']==4) && empty($request['user_id'])) $query->where('shop_transactions.by_user',$logged_user['user_id']); // self data for parent and child 
                                                        // if ($logged_user['user_role']==3 && $request['user_id']) $query->where('shop_transactions.by_user',$request['user_id']);  //for child data
                                                        // if ($logged_user['user_role']==5) $query->wheredate('shop_transactions.created_at',date('Y-m-d'));  //for shopkeeper
                                                        // }) 
                                                        ->whereIn('shop_transactions.shop_id', $shops_by_categories)
                                                        ->whereYear('shop_transactions.created_at', '=', date('Y'))
                                                        ->whereMonth('shop_transactions.created_at',"=",$i)
                                                        ->groupBy('shop_transactions.shop_id') 
                                                        ->orderBy('shop_transactions.created_at', 'DESC')->get()->toArray();

                                                       
                                                        $shop_sales[]=array_sum(array_column($shop_sales_per_month, 'total_sale'));                                                                                                      

            } 

                 $cat_sales_by_month[]=[
                  'name'=>$cat['shop_cat_name'],
                  'data'=>$shop_sales
                  ];
                
        }

        $result['cat_sales_by_month']=$cat_sales_by_month;

                                                                 
                                                                                            
        // $result['all_shops']=['Shop 1', 'Shop 2', 'Shop 3', 'Shop 4', 'Shop 5', 'Shop 6', 'Shop 7', 'Shop 8', 'Shop 9', 'Shop 10'];
        // $result['shops_earn']=[280, 120, 82, 73, 95, 140, 180, 60, 84, 150];       
      
        return view('admin/dashboard',$result);
    }

   
    public function logout(Request $request)
    {
        $request->session()->forget('admin_token');
        return redirect('admin');
    }


    public function send_notification($request)
    {    
        //    $msg = urlencode($msg);
           $data = array(
            'title'=>$request['title'],
            'sound' => "default",
            'msg'=>urlencode($request['msg']),
            'data'=>'Data',
            'body'=>$request['body'],
            'color' => "#79bc64"
        );
   
    $fields = array(           
        'to'=>$request['to'],
        'notification'=>$data,
        "priority" => "high",
    );

        $headers = array(
            'Authorization: key='.env("NOTIFICATION_AUTH_KEY"),
            'Content-Type: application/json'
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        curl_close( $ch );
        return json_decode($result);      
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

    function bank_transfer()
    {
        require 'vendor/autoload.php';

        // $stripe = new \Stripe\Stripe::setApiKey('sk_test_51LfGgJSCia5qiodpIeTGpYQTrWuFs8AMuCXRu3SvVF7SeyRNHn4eroQOhpUWUWCKbAIsFc3kuENWh0RBYdnYhnIn00YrHjImwW');
        $stripe = new \Stripe\StripeClient('sk_test_51LfGgJSCia5qiodpIeTGpYQTrWuFs8AMuCXRu3SvVF7SeyRNHn4eroQOhpUWUWCKbAIsFc3kuENWh0RBYdnYhnIn00YrHjImwW');
 
       
        \Stripe\Stripe::setApiKey('sk_test_51LfGgJSCia5qiodpIeTGpYQTrWuFs8AMuCXRu3SvVF7SeyRNHn4eroQOhpUWUWCKbAIsFc3kuENWh0RBYdnYhnIn00YrHjImwW');

        $customer = \Stripe\Customer::create();

        \Stripe\Stripe::setApiVersion("2022-08-01");

            $intent = \Stripe\PaymentIntent::create([
                'customer' => $customer->id,
                'amount' => 1099,
                'currency' => 'AED',
                'payment_method_types' => ['card']
            ]);

          return $intent;

        $payment_intent= \Stripe\PaymentIntent::create([
            'amount' => 1099,
            'currency' => 'AED',
            'payment_method_types' => ['card'],
            ]);

        //   return $payment_intent;

        $checkout_session=$stripe->refunds->create([
        'payment_intent' => $payment_intent->id,
        'amount' => 1099,
        'instructions_email' => 'suraj@appcartsystems.com'
        ]);

        return $checkout_session;
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