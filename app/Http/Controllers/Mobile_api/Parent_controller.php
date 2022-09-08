<?php

namespace App\Http\Controllers\Mobile_api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Mobile_api\Login_controller;

use App\Models\User_model;
use App\Models\Auth_users;
use App\Models\Parent_child_model; 
use App\Models\Wallet_model;
use App\Models\Wallet_transaction_model;

class Parent_controller extends Controller
{
    public function __construct()
    {               
        $this->middleware('CheckApiToken:app');
        
    }

    public function add_child(Request $request)
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
                        $data=array('status'=>true,'msg'=>'User registered successfully','token'=>$gen_token);
                    } else {                        
                        $data=array('status'=>false,'msg'=>'Something went wrong');
                    }


            } else {         

                $data=array('status'=>false,'msg'=>'User already exists');
            }

           
         }

         echo json_encode($data); 
    }

    public function getall_children(Request $request)
    {       
        $data=array('status'=>false,'msg'=>'Data not found','children'=>array());

        $logged_user=Auth::mobile_app_user($request['token']);

        $children=User_model::select('users.*','parent_child.parent_id','wallet.balance')      
        ->leftjoin('parent_child', 'users.user_id', '=', 'parent_child.child_id')    
        ->leftjoin('wallet', 'parent_child.child_id', '=', 'wallet.user_id')    
        ->where('parent_child.parent_id',$logged_user['user_id'])
        ->get()->toArray();     
        if(!empty($children))
        {
            $data=array('status'=>true,'msg'=>'Data found','children'=>$children);
        } 
        
        echo json_encode($data);
    }



    public function add_money_to_wallet(Request $request)
    {       
        $data=array('status'=>false,'msg'=>'Data not found');
        if($request['user_id'] && $request['amount'])
        {  
            $users_wallet_exists=Wallet_model::where('user_id',$request['user_id'])->first();

            $logged_user=Auth::mobile_app_user($request['token']);

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

            } else {

                $users_wallet_exists->balance=$users_wallet_exists->balance + $request['amount'];
                $users_wallet_exists->updated_at=date('Y-m-d H:i:s');
                $update=$users_wallet_exists->save();
                if($update)
                {
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

            }

        }      
        
        echo json_encode($data);
    }

   
}
