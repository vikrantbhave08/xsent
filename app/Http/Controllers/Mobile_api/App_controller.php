<?php

namespace App\Http\Controllers\Mobile_api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;


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
            ->where(function ($query) use ($request) {
                if (empty($request['for_user'])) $query->where('users.user_id',$request['user_id']);                       
                if (!empty($request['for_user'])) $query->where('users.user_id',$request['for_user']);                       
             })    
            ->first(); 
    }

    public function get_users_profile(Request $request)
    {
        $data=array('status'=>false,'msg'=>'Data not found');

        $logged_user=Auth::mobile_app_user($request['token']);

        if(!empty($request['user_id']))
        {
            $logged_user['for_user']=$request['user_id'];
        }
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


                    $title=$request['user_role']==4 ? 'Your parent is registred you on xsent.' : 'Shop Owner registred you on xsent.';

                    $details = [
                        'title' => $title ,
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
    

    public function transaction_summary(Request $request)  // 1) for shop owner 
    {        

        $data=array('status'=>false,'msg'=>'Data not found','shop_transactions'=>array());

        $logged_user=Auth::mobile_app_user($request['token']);

        $shop_transactions=array();
        $j=0;
        for($i=1; $i<=12; $i++)
        {          
              
            $transactions=Shop_transaction_model::select('shop_transactions.*','users.first_name','users.last_name','shops.shop_name')
                                                       ->leftjoin('users', 'shop_transactions.by_user', '=', 'users.user_id')    
                                                       ->leftjoin('shops', 'shop_transactions.shop_id', '=', 'shops.shop_id') 
                                                       ->where(function ($query) use ($request,$logged_user) {
                                                        if (!empty($request['shop_gen_id'])) $query->where('shops.shop_gen_id',$request['shop_gen_id']);  
                                                        if (($logged_user['user_role']==3 || $logged_user['user_role']==4) && empty($request['user_id'])) $query->where('shop_transactions.by_user',$logged_user['user_id']); // self data for parent and child 
                                                        if ($logged_user['user_role']==3 && $request['user_id']) $query->where('shop_transactions.by_user',$request['user_id']);  //for child data
                                                        }) 
                                                       ->whereYear('shop_transactions.created_at', '=', date('Y'))
                                                       ->whereMonth('shop_transactions.created_at',"=",$i)->get()->toArray();

            $monthly_transactions=array();
            foreach($transactions as $key=>$res)
            {
                $monthly_transactions[$key]=$res;
                $monthly_transactions[$key]['date']=date('d/m/Y', strtotime($res['created_at']));
                $monthly_transactions[$key]['time']=date('h:i A', strtotime($res['created_at']));
            }

            if(!empty($monthly_transactions))
            {               
                $shop_transactions[$j]['month']=date('F', mktime(0,0,0,$i, 1, date('Y'))); 
                $shop_transactions[$j]['data']=$monthly_transactions; 
                $j++;
            }
        }

        if(!empty($shop_transactions))
        {
            $data=array('status'=>true,'msg'=>'Data found','shop_transactions'=>$shop_transactions);
        }

        echo json_encode($data);

    }

    public function get_users_wallet(Request $request)  
    {
        $data=array('status'=>false,'msg'=>'Data not found');

        if($request['user_id'])
        {  
           $users_wallet=Wallet_model::select('wallet.wallet_id','wallet.max_limit_per_day','wallet.max_limit_per_month','wallet.low_balance_alert')->where('user_id',$request['user_id'])->first();

           if(!empty($users_wallet)) 
           { 
            $data=array('status'=>true,'msg'=>'Data found','wallet_data'=>$users_wallet->toArray());
           }
        } 

        echo json_encode($data);
    }

    public function update_users_wallet(Request $request)  
    {
        $data=array('status'=>false,'msg'=>'Data not found');

        if($request['user_id'])
        {  
           $users_wallet=Wallet_model::where('user_id',$request['user_id'])->first();

           if(empty($users_wallet))
           {

            $users_data=User_model::where('user_id', $request['user_id'])->first();
            $create_wallet=Wallet_model::create([                        
                                'user_id' => $request['user_id'],
                                'user_role' => $users_data->user_role,
                                'balance' => '',
                                'max_limit_per_day' => $request['max_limit_per_day'] ? $request['max_limit_per_day'] : '' ,
                                'max_limit_per_month' => $request['max_limit_per_month'] ? $request['max_limit_per_month'] : '',
                                'low_balance_alert' => $request['low_balance_alert'] ? $request['low_balance_alert'] : '',
                                'created_at' => date('Y-m-d H:i:s'),
                                'updated_at' => date('Y-m-d H:i:s')
                                ])->wallet_id;

           } else {
               
               $request['max_limit_per_day'] ? $users_wallet->max_limit_per_day=$request['max_limit_per_day'] : '';
               $request['max_limit_per_month'] ? $users_wallet->max_limit_per_month=$request['max_limit_per_month'] :'';
               $request['low_balance_alert'] ? $users_wallet->low_balance_alert=$request['low_balance_alert'] :'';
               $users_wallet->updated_at=date('Y-m-d H:i:s');
    
               $is_update=$users_wallet->save();
           }


           $data=array('status'=>true,'msg'=>'Wallet updated');
         
        }

        echo json_encode($data);
    }

    public function add_money_to_wallet(Request $request)  // 1) parent to child 2) child to shop
    {       
        $data=array('status'=>false,'msg'=>'Data not found');

        $logged_user=Auth::mobile_app_user($request['token']);

        if($request['user_id'] && $request['amount'])
        {  
            $beneficiary_user=User_model::where('user_id', $request['user_id'])->first();
            $for_user_role=$beneficiary_user->user_role==3 ? 2 : $beneficiary_user->user_role;
            
            $users_wallet_exists=Wallet_model::where('user_id',$request['user_id'])->first();

            $day_limit=$month_limit=true;
            if($logged_user['user_role']==4)
            {

                for($i=0; $i<2; $i++)
                {
                    $from_wallet=Wallet_model::select('wallet.*',DB::raw('ifnull(SUM(wallet_transaction.credit),0) as max_transaction'))
                    ->leftjoin('wallet_transaction', 'wallet.wallet_id', '=', 'wallet_transaction.wallet_id')
                    ->where('wallet.user_id',$logged_user['user_id'])
                    ->where('wallet.user_role',$logged_user['user_role'])
                    ->where(function ($query) use ($request,$logged_user,$i) { 
                        if($i==0) $query->WhereDate('wallet_transaction.created_at', Carbon::today()); //only for children                   
                        if($i==1) $query->whereMonth('wallet_transaction.created_at',"=",date('m')); //only for children                   
                     })                                 
                     ->groupBy('wallet_transaction.user_id') 
                    //  ->having('max_transaction','<=','wallet.balance')                                    
                     ->first(); 
                     if($i==0 && !empty($from_wallet))
                     {

                        $day_limit = (empty($from_wallet->max_limit_per_day) || $from_wallet->max_limit_per_day >= $from_wallet->max_transaction )? true : false ;

                    } else if($i==1 && !empty($from_wallet))
                    {
                         $month_limit = (empty($from_wallet->max_limit_per_month) || $from_wallet->max_limit_per_month>=$from_wallet->max_transaction) ? true : false ;

                     }
                  
                }

            } else {
                
                $from_wallet=Wallet_model::where('wallet.user_id',$logged_user['user_id'])->where('wallet.user_role',$logged_user['user_role'])->first();
            }                       
            
         
                                  
            $from_wallet_balance=0;            
            if(!empty($from_wallet))
            {
                $from_wallet_balance=$from_wallet->balance;
            }


            if($from_wallet_balance>$request['amount'])
            {    

                    if($day_limit)
                    {
                        if($month_limit)
                        {

                            if(empty($users_wallet_exists))
                            {               
                                
                                $create_wallet=Wallet_model::create([                        
                                                    'user_id' => $request['user_id'],
                                                    'user_role' => $for_user_role,
                                                    'balance' => $request['amount'],
                                                    'max_limit_per_day' => '',
                                                    'max_limit_per_month' => '',
                                                    'low_balance_alert' => '',
                                                    'created_at' => date('Y-m-d H:i:s'),
                                                    'updated_at' => date('Y-m-d H:i:s')
                                                    ])->wallet_id;
                                if($create_wallet)
                                {
                                    if(!empty($request['request_id']))
                                    {
                                        $request_data=Amount_requests_model::where('amt_request_id',$request['request_id'])->first();
                                        $request_data->status=1;
                                        $request_data->save();
                                    }

                                    if(!empty($request['shop_gen_id']))
                                    {
                                        $shop_detail=Shops_model::where('shop_gen_id',$request['shop_gen_id'])->first();

                                        Shop_transaction_model::create([ 
                                            'by_user' => $logged_user['user_id'],
                                            'shop_id' => $shop_detail->shop_id,
                                            'amount' => $request['amount'],
                                            'created_at' => date('Y-m-d H:i:s'),
                                            'updated_at' => date('Y-m-d H:i:s')
                                        ])->shop_trans_id;

                                    }

                                    Wallet_transaction_model::create([                        
                                        'from_user' => $logged_user['user_id'],
                                        'from_role' => $logged_user['user_role'],
                                        'user_id' => $request['user_id'],
                                        'to_role' => $for_user_role,
                                        'wallet_id' => $create_wallet,
                                        'credit' => $request['amount'],
                                        'debit' => '',
                                        'payment_gate_id' => '',
                                        'status_msg' => 'Added money from parent to student',
                                        'created_at' => date('Y-m-d H:i:s'),
                                        'updated_at' => date('Y-m-d H:i:s')
                                        ])->wallet_id;    
                                        
                                        $remaining_balance=$from_wallet->balance - $request['amount'];
                                        $from_wallet->balance=$remaining_balance;
                                        $from_wallet->save();

                                    $data=array('status'=>true,'msg'=>'Money added into the wallet','remaining_balance'=>$remaining_balance);
                                }else{
                                    $data=array('status'=>false,'msg'=>'Money not added');
                                }

                            

                               } else {

                                $users_wallet_exists->balance=$users_wallet_exists->balance + $request['amount'];
                                $users_wallet_exists->updated_at=date('Y-m-d H:i:s');
                                $update=$users_wallet_exists->save();
                                if($update)
                                {

                                    if(!empty($request['request_id']))
                                    {
                                        $request_data=Amount_requests_model::where('amt_request_id',$request['request_id'])->first();
                                        $request_data->status=1;
                                        $request_data->save();
                                    }

                                    if(!empty($request['shop_gen_id']))
                                    {
                                        $shop_detail=Shops_model::where('shop_gen_id',$request['shop_gen_id'])->first();

                                        Shop_transaction_model::create([ 
                                            'by_user' => $logged_user['user_id'],
                                            'shop_id' => $shop_detail->shop_id,
                                            'amount' => $request['amount'],
                                            'created_at' => date('Y-m-d H:i:s'),
                                            'updated_at' => date('Y-m-d H:i:s')
                                        ])->shop_trans_id;

                                    }

                                    Wallet_transaction_model::create([                        
                                        'from_user' => $logged_user['user_id'],
                                        'from_role' => $logged_user['user_role'],
                                        'user_id' => $request['user_id'],
                                        'to_role' => $for_user_role,
                                        'wallet_id' => $users_wallet_exists->wallet_id,
                                        'credit' => $request['amount'],
                                        'debit' => '',
                                        'payment_gate_id' => '',
                                        'status_msg' => 'Added money from parent to student',
                                        'created_at' => date('Y-m-d H:i:s'),
                                        'updated_at' => date('Y-m-d H:i:s')
                                        ])->wallet_id;

                                        $remaining_balance=$from_wallet->balance - $request['amount'];
                                        $from_wallet->balance=$remaining_balance;
                                        $from_wallet->save();

                                    $data=array('status'=>true,'msg'=>'Money added into the wallet','remaining_balance'=>$remaining_balance);
                                } else {
                                    $data=array('status'=>false,'msg'=>'Money not added');

                                }

                            }
                        } else {
                            
                            $data=array('status'=>false,'msg'=>'Monthly wallet limit exceeded');
                        }
                    } else {                        
                        $data=array('status'=>false,'msg'=>'One day wallet limit exceeded');
                      }
                } else {
                    $data=array('status'=>false,'msg'=>'Your wallet has insufficient balance');
                }

            }      
        
        echo json_encode($data);
    }

    public function get_shops_by_owner(Request $request)
    {
        $data=array('status'=>false,'msg'=>'Data not found','shops'=>array());

        $logged_user=Auth::mobile_app_user($request['token']);

        $shops=Shops_model::select('users.first_name','users.last_name','shops.owner_id','shops.shop_name','shops.shop_id','shops.shop_gen_id')
                    ->leftjoin('users', 'shops.owner_id', '=', 'users.user_id') 
                    ->where(function ($query) use ($request,$logged_user) {
                        if (!empty($request['shop_gen_id'])) $query->where('shops.shop_gen_id', $request['shop_gen_id']);                       
                        if ($logged_user==2 || $logged_user==5) $query->where('shops.owner_id',$logged_user['user_id']);                       
                    })
                    ->get()->toArray();
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

    public function add_request(Request $request)
    {
        $data=array('status'=>false,'msg'=>'Data not found');

        $logged_user=Auth::mobile_app_user($request['token']);

        if($request['amount'])
         {

            if($logged_user['user_role']==2 || $logged_user['user_role']==3)
            {
                $wallet=Wallet_model::where('user_id',$logged_user['user_id'])->first();
                $requested_amt=Amount_requests_model::select(DB::raw('ifnull(SUM(amount_requests.request_amount),0) as total_req_amt'))
                                                        ->where('amount_requests.by_user',$logged_user['user_id'])
                                                        ->where('amount_requests.by_role',$logged_user['user_role'])
                                                        ->where('status',0)->groupBy('amount_requests.by_user')->first();
                
                if(!empty($requested_amt) && !empty($wallet))
                {
                    if(($requested_amt->total_req_amt+$request['amount'])<=$wallet->balance)
                    {
                        $request_flag=true;
                    }else{
                        $request_flag=false;
                    }
                }else{
                    $request_flag=false;
                }
            } else {                
                $request_flag=true;
            }
       
            if($request_flag)
            {
            $amt_request=Amount_requests_model::create([                        
                'by_user' => $logged_user['user_id'],
                'by_role' => $logged_user['user_role'],
                'to_user' => $logged_user['user_role']==4 ? Parent_child_model::where('child_id',$logged_user['user_id'])->first()->parent_id : 0,
                'request_amount' => $request['amount'],                   
                'reason' => $request['reason'] ? $request['reason'] : "",                   
                'date_of_expenditure' => $request['date_of_expenditure'] ? $request['date_of_expenditure'] : "",                   
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
                ])->amt_request_id;

                if($amt_request)
                {
                    $data=array('status'=>true,'msg'=>'Requests added successfully');                   
                }
            } else {
                $data=array('status'=>false,'msg'=>'Sum of requested amount is more than wallet balance');     
            }

         }

         echo json_encode($data);
    }

    public function topup_history(Request $request)
    {
        $data=array('status'=>false,'msg'=>'Data not found','topup'=>array());

        $logged_user=Auth::mobile_app_user($request['token']);

        $topup_history=array();
        $j=0;
        for($i=1; $i<=12; $i++)
        {
            $topup=Wallet_transaction_model::from('wallet_transaction as wt')
                                            ->select('wt.credit','wt.created_at')
                                            ->where(function ($query) use ($request,$logged_user) {                                             
                                                if (!empty($request['user_id'])) $query->where('wt.user_id',$request['user_id']);     // user (child) topup history                                              
                                                if (empty($request['user_id'])) $query->where('wt.user_id',$logged_user['user_id']);     // user (child) history                                              
                                            })                                            
                                            // ->where('wt.from_user',0)
                                            ->whereYear('wt.created_at', '=', date('Y'))
                                            ->whereMonth('wt.created_at',"=",$i)
                                            ->get()->toArray();

            $history=array();
            foreach($topup as $key=>$res)
            {
                $history[$key]=$res;
                $history[$key]['topup_date']=date('d M Y', strtotime($res['created_at']));          
                $history[$key]['topup_time']=date('h:i A', strtotime($res['created_at']));          
            }

            if(!empty($history))
            {
                $topup_history[$j]['month']=date('F', mktime(0,0,0,$i, 1, date('Y'))); 
                $topup_history[$j]['data']=$history;
                $j++;
            }            
        }

        if(!empty($topup_history))
        {
            $data=array('status'=>true,'msg'=>'Data found','topup'=>$topup_history);
        }

       
         echo json_encode($data);
    }

    public function request_money_history(Request $request)
    {
        $data=array('status'=>false,'msg'=>'Data not found','requests'=>array());

        $logged_user=Auth::mobile_app_user($request['token']);

        $users_requests=array();

        $childrens=$logged_user['user_role']==3 && $request['user_id']==0 ? Parent_child_model::select('child_id')->where('parent_id',$logged_user['user_id'])->get()->toArray() : array();
       
        $childrens = !empty($childrens) ? array_column($childrens,'child_id') : array(); 
             

        $j=0;
        for($i=1; $i<=12; $i++)
        {   
            
        $money_requests=Amount_requests_model::select('users.first_name','users.last_name','amount_requests.*')
                                                ->leftjoin('users', 'amount_requests.by_user', '=', 'users.user_id') 
                                                ->where(function ($query) use ($request,$logged_user,$childrens) {
                                                    if ( $logged_user['user_role']==2 || ($logged_user['user_role']==3 && $request['user_id']=="") || $logged_user['user_role']==4 ) $query->where('amount_requests.by_user',$logged_user['user_id']);  //parent,child,owner request self history
                                                    if (!empty($request['user_id'])) $query->where('amount_requests.by_user',$request['user_id']);     // user (child) history
                                                    if ($logged_user['user_role']==3 && $request['user_id']==0) $query->whereIn('amount_requests.by_user',$childrens);     // user (child) history
                                                })
                                                ->whereYear('amount_requests.created_at', '=', date('Y'))
                                                ->whereMonth('amount_requests.created_at',"=",$i)
                                                ->get()->toArray();

        $requested=array();
        foreach($money_requests as $key=>$res)
        {
            $requested[$key]=$res;
            $requested[$key]['requested_date']=date('d M Y', strtotime($res['created_at'])).' | '.date('h:i A', strtotime($res['created_at']));          
        }

        if(!empty($requested))
        {
            $users_requests[$j]['month']=date('F', mktime(0,0,0,$i, 1, date('Y'))); 
            $users_requests[$j]['data']=$requested;
            $j++;
        }


        }

        if(!empty($users_requests))
        {
            $data=array('status'=>true,'msg'=>'Data found','requests'=>$users_requests);
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


    public function mobile_verified(Request $request)
    {
        $data=array('status'=>false,'msg'=>'Data not found');

        if($request['user_id'])
        {            
            $user_data=User_model::where('user_id',$request['user_id'])->first();
            $user_data->contact_verify=1;
            $update=$user_data->save();
            $data=array('status'=>true,'msg'=>'Contact verified successfully');
        }

        echo json_encode($data); 
    }
}
