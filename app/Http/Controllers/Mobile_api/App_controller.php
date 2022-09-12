<?php

namespace App\Http\Controllers\Mobile_api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;


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

class App_controller extends Controller
{
    
    public function __construct()
    {               
        $this->middleware('CheckApiToken:app');        
    }


    public function parent_balance($request)
    {
        return Wallet_model::where('user_id',$request['user_id'])->first();
    }
    
    public function profile_data($request)
    {
        return User_model::select('users.*',DB::raw('ifnull(wallet.balance,0) as balance')) 
            ->leftjoin('wallet', 'users.user_id', '=', 'wallet.user_id')    
            ->where('users.user_id',$request['user_id'])
            ->first(); 
    }

    public function get_users_profile(Request $request)
    {
        $data=array('status'=>false,'msg'=>'Data not found');

        $logged_user=Auth::mobile_app_user($request['token']);

        $profile_data=$this->profile_data($logged_user);
        
        if(!empty($profile_data))
        {
            $data=array('status'=>true,'msg'=>'Profile data','profile_data'=>$profile_data->toArray());
        }

        return $data;       
    }
    
    public function add_user(Request $request)
    {   
        $data=array('status'=>false,'msg'=>'Data not found');

        if($request['email'] && $request['password'] && $request['user_role'] && $request['name'])
         {
            $fullname=explode(" ",$request['name']);
            $request['first_name']=count($fullname)>=1 ? $fullname[0] : '' ;
            $request['last_name']=count($fullname)>1 ? $fullname[1] : '' ;

            $request_data=$request->all();
            $check_user_exists=Login_controller::check_user_and_validate(array('email'=>$request_data['email'],'user_role'=>$request['user_role']));  //here user role 4 is for child
           
            if(!$check_user_exists['status'])
            {
                $logged_user=Auth::mobile_app_user($request['token']);

                $user=User_model::where('username',$request['email'])->first();

                $gen_token=sha1(mt_rand(11111,99999).date('Y-m-d H:i:s'));

              
                if(empty($user))
                {
                    $create_user = User_model::create([                        
                        'user_role' => $request['user_role'],
                        'first_name' => $request['first_name'],
                        'last_name' => $request['last_name'],
                        'username' => $request['email'],
                        'email' => $request['email'],
                        'contact_no' => $request['contact_no'],
                        'password' => sha1($request['password'].'appcart systemts pvt ltd'),
                        'passphrase' => $request['password'],
                        'country' => $request['country'],
                        'city' => $request['city'],
                        'birth_date' => $request['birth_date'],
                        'gender' => $request['gender'],
                        'university' => $request['university'],
                        'token' => $gen_token,
                        'created_at' =>  date('Y-m-d H:i:s'),
                        'updated_at' =>  date('Y-m-d H:i:s')
                    ])->user_id;

                    if($create_user)
                    {
                        Parent_child_model::create([  
                            'parent_id' => $logged_user['user_id'],
                            'child_id' => $create_user,
                            'created_at' =>  date('Y-m-d H:i:s'),
                            'updated_at' =>  date('Y-m-d H:i:s')
                        ])->assign_id;
                    }

                    $user=array(
                        'user_id' => $create_user,
                        'user_role' => $request['user_role']
                    );


                    $details = [
                        'title' => 'Your parent is registred you on xsent.',
                        'body' => 'See your credentials below.',
                        'username' => $request['email'],
                        'password' => $request['password']
                    ];
                   
                    $email_response=\Mail::to($request['email'])->send(new \App\Mail\SendMail($details));

                } else {
                    $user=$user->toArray();
                } 
               

                    $create_auth_user = Auth_users::create([
                        'user_id' => $user['user_id'],
                        'user_role' => $request['user_role'],
                        'users_token' => $gen_token,
                        'fcm_token' => $request['fcm_token'],
                        'created_at' =>  date('Y-m-d H:i:s'),
                        'updated_at' =>  date('Y-m-d H:i:s')
                    ])->auth_id;    
                    
                    if($create_auth_user)
                    {            
                        if(!empty($request['shop_id']))
                        {
                            $shopkeeper=Shopkeepers_model::create([                        
                                'salesperson_id' => $user['user_id'],
                                'shop_id' => $request['shop_id'],
                                'owner_id' => $logged_user['user_id'],                 
                                'created_at' => date('Y-m-d H:i:s'),
                                'updated_at' => date('Y-m-d H:i:s')
                                ])->shopkeeper_id;
                        }

                        $data=array('status'=>true,'msg'=>'User added successfully','token'=>$gen_token);
                    } else {                        
                        $data=array('status'=>false,'msg'=>'Something went wrong');
                    }


            } else {         

                $data=array('status'=>false,'msg'=>'User already exists');
            }

           
         }

         echo json_encode($data); 
    }
   

    public function get_children(Request $request)
    {       
        $data=array('status'=>false,'msg'=>'Data not found','children'=>array());
        
        $logged_user=Auth::mobile_app_user($request['token']);

        $from_wallet=$this->parent_balance($logged_user);

        $from_wallet_balance=0;
        
        if(!empty($from_wallet))
        {
            $from_wallet_balance=$from_wallet->balance;
        }

        $children=User_model::select('users.*','parent_child.parent_id',DB::raw('ifnull(wallet.balance,0) as balance'),DB::raw('ifnull(SUM(shop_transactions.amount),0) as spend_amt'))
        ->leftjoin('parent_child', 'users.user_id', '=', 'parent_child.child_id')    
        ->leftjoin('wallet', 'parent_child.child_id', '=', 'wallet.user_id')  
        ->leftjoin('shop_transactions', 'users.user_id', '=', 'shop_transactions.by_user')  
        ->where(function ($query) use ($request) {
            if (!empty($request['user_id'])) $query->where('parent_child.child_id',$request['user_id']);                       
         }) 
        ->where('parent_child.parent_id',$logged_user['user_id'])
        ->groupBy('shop_transactions.by_user')->get()->toArray();     
        if(!empty($children))
        {
            $data=array('status'=>true,'msg'=>'Data found','children'=>$children,'parent_balance'=>(string)$from_wallet_balance);
        } 
        
        echo json_encode($data);
    }
    

    public function get_wallet_transactions(Request $request)  // 1) for shop owner 
    {
        $data=array('status'=>false,'msg'=>'Data not found');
    }

    public function add_money_to_wallet(Request $request)  // 1) parent to child 2) child to shop
    {       
        $data=array('status'=>false,'msg'=>'Data not found');
        if($request['user_id'] && $request['amount'])
        {  
            $users_wallet_exists=Wallet_model::where('user_id',$request['user_id'])->first();

            $logged_user=Auth::mobile_app_user($request['token']);

            $from_wallet=Wallet_model::where('user_id',$logged_user['user_id'])->first(); 

            $from_wallet_balance=0;
            
            if(!empty($from_wallet))
            {
                $from_wallet_balance=$from_wallet->balance;
            }

            if($from_wallet_balance>=$request['amount'])
            {
            if(empty($users_wallet_exists))
            {               
                
                $create_wallet=Wallet_model::create([                        
                                    'user_id' => $request['user_id'],
                                    'balance' => $request['amount'],
                                    'max_limit_per_day' => '',
                                    'max_limit_per_month' => '',
                                    'low_balance_alert' => '',
                                    'created_at' => date('Y-m-d H:i:s'),
                                    'updated_at' => date('Y-m-d H:i:s')
                                    ])->wallet_id;
                if($create_wallet)
                {
                    Wallet_transaction_model::create([                        
                        'from_user' => $logged_user['user_id'],
                        'user_id' => $request['user_id'],
                        'wallet_id' => $create_wallet,
                        'credit' => $request['amount'],
                        'debit' => '',
                        'payment_gate_id' => '',
                        'status_msg' => 'Added money from parent to student',
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                        ])->wallet_id;                       

                    $data=array('status'=>true,'msg'=>'Money added into the wallet');
                }else{
                    $data=array('status'=>false,'msg'=>'Money not added');
                }

                $from_wallet->balance=$from_wallet->balance - $request['amount'];
                $from_wallet->save();

            } else {

                $users_wallet_exists->balance=$users_wallet_exists->balance + $request['amount'];
                $users_wallet_exists->updated_at=date('Y-m-d H:i:s');
                $update=$users_wallet_exists->save();
                if($update)
                {

                    if(!empty($request['shop_id']))
                    {

                        Shop_transaction_model::create([ 
                            'by_user' => $logged_user['user_id'],
                            'shop_id' => $request['shop_id'],
                            'amount' => $request['amount'],
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s')
                        ])->shop_trans_id;

                    }

                    Wallet_transaction_model::create([                        
                        'from_user' => $logged_user['user_id'],
                        'user_id' => $request['user_id'],
                        'wallet_id' => $users_wallet_exists->wallet_id,
                        'credit' => $request['amount'],
                        'debit' => '',
                        'payment_gate_id' => '',
                        'status_msg' => 'Added money from parent to student',
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                        ])->wallet_id;

                    $data=array('status'=>true,'msg'=>'Money added into the wallet');
                } else {
                    $data=array('status'=>false,'msg'=>'Money not added');

                }

                $from_wallet->balance=$from_wallet->balance - $request['amount'];
                $from_wallet->save();

            }
        } else {
            $data=array('status'=>false,'msg'=>'Insufficient balance');
        }

        }      
        
        echo json_encode($data);
    }

    public function get_shops_by_owner(Request $request)
    {
        $data=array('status'=>false,'msg'=>'Data not found','shops'=>array());

        $logged_user=Auth::mobile_app_user($request['token']);

        $shops=Shops_model::select('users.*','shops.shop_name','shops.shop_id','shops.shop_gen_id')
                    ->leftjoin('users', 'shops.owner_id', '=', 'users.user_id') 
                    ->where(function ($query) use ($request) {
                        if (!empty($request['shop_gen_id'])) $query->where('shops.shop_gen_id', $request['shop_gen_id']);                       
                    })
                    ->where('shops.owner_id',$logged_user['user_id'])->get()->toArray();
        if(!empty($shops))
        {
            $data=array('status'=>true,'msg'=>'Shops','shops'=>$shops);
        }

        echo json_encode($data);
    }

    public function add_shop(Request $request)
    {
        $data=array('status'=>false,'msg'=>'Data not found');

        $logged_user=Auth::mobile_app_user($request['token']);

        if($request['shop_name'])
         {
            $shop_exists=Shops_model::where('shop_name',$request['shop_name'])->first();
            if(empty($shop_exists))
            {              
                $create_shop=Shops_model::create([                        
                    'owner_id' => $logged_user['user_id'],
                    'shop_name' => $request['shop_name'],                   
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                    ])->shop_id;

                    if($create_shop)
                    {
                        $shop_gen_id='sh_'.base64_encode($logged_user['user_id'])."_" .base64_encode($create_shop);
                        $shop=Shops_model::where('shop_id',$create_shop)->first();
                        $shop->shop_gen_id=$shop_gen_id;
                        $shop->save();
                    }

                    $data=array('status'=>true,'msg'=>'Shop created successfully','shop_gen_id'=>$shop_gen_id);

            } else {
                    $data=array('status'=>false,'msg'=>'Shop already exists');
            }
         }

         echo json_encode($data);
    }

    public function qr_code_generate(Request $request)
    {
        // $image = \QrCode::format('png')
        // ->merge(public_path('images/download.png'), 0.2, true)
        // ->size(500)
        // ->errorCorrection('H')
        // ->generate('A simple example of QR code!');

        // return response($image)->header('Content-type','image/png');

        return \QrCode::size(200)->generate('{name:"suraj"}');     
        
        // return \QrCode::format('png')->merge(public_path('images/logo.png'), 0.3, true)->errorCorrection('H')->size(300)
        // ->generate('{name:"suraj"}', public_path('images/qrcode.png'));

        // return view('qrCode');

    }

    

    public function reset_password(Request $request)
    {   
       
        $data=array('status'=>false,'msg'=>'Data not found');
        
        if($request['token'] && $request['current_password'] && $request['new_password'])
        {                                           
                // $mobile_user=Auth::mobile_app_user($request['token']);
                     
                $auth_user=User_model::select('users.*','auth_user.auth_id')
                                    ->leftjoin('auth_user', 'users.user_id', '=', 'auth_user.user_id')
                                    ->where('auth_user.users_token',$request['token'])
                                    ->where('users.password',sha1($request['current_password'].'appcart systemts pvt ltd'))->first();               
               
                if(!empty($auth_user))
                {
                    $auth_user->password = sha1($request['new_password'].'appcart systemts pvt ltd');
                    $auth_user->passphrase = $request['new_password'];
                    $auth_user->updated_at= date('Y-m-d H:i:s');
                    $auth_user->save();
                    $data=array('status'=>true,'msg'=>'Password changed successfully');                                   
                } else {

                    $data=array('status'=>false,'msg'=>'Current password does not match');                                   
                }
            
         }

        
        echo json_encode($data);
    }
}
