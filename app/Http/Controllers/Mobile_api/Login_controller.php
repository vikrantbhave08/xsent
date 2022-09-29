<?php

namespace App\Http\Controllers\Mobile_api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use URL;

use \ArrayObject;

use App\Models\User_model;
use App\Models\Auth_users;
use App\Models\Shops_model;
use App\Models\Shopkeepers_model;
use App\Models\Cities_model;
use App\Models\Province_model;
use App\Models\Shop_cat_model;
use App\Models\Parent_child_model;

class Login_controller extends Controller
{
    public function __construct()
    {       
       
    }

    public function send_verification_link(Request $request)
    {        
        
        $data=array('flag'=>false,'msg'=>'Invalid User');
        
        if($request['access_tkn'])
        {
            $user= User_model::select('users.*')->where('token',$request['access_tkn'])->first();  
            
            if(!empty($user))
            {    
                $updated_date=date('Y-m-d H:i:s');
                $user->updated_at=$updated_date;
                $user->save();

                $red_url=URL::to('/api/verify-email?access_tkn='.$request['access_tkn'].'_xsent_'.strtotime($updated_date));

                      $details = [
                        'title' => 'Click on verification link to verify email',
                        'body' => $red_url
                    ];
                   
                    $email_response=\Mail::to($user->email)->send(new \App\Mail\SendMail($details));

                  

             
                $data=array('flag'=>true,'msg'=>'Verification link send successfully');
            } else {
                $data=array('flag'=>false,'msg'=>'Invalid User');
            }
        }

        
        echo json_encode($data);
    }

    public function verify_email(Request $request)
    {     

        $data=array('status'=>false,'msg'=>'Data not found');

        // echo $request['access_tkn']='4f4a2948954fdfceb2b9a589673b108f921d455f_xsent_'.strtotime('2022-09-20 09:18:44');
        // exit;
      
        if($request['access_tkn'])
        {
            $last_que=explode('_',$request['access_tkn']);

            $user= !empty($last_que[0]) ? User_model::select('users.*')->where('token',$last_que[0])->first() : array() ;
            if(!empty($user))
            {        
                $link_expire=!empty($last_que[2]) && round((strtotime(date('Y-m-d H:i:s')) - strtotime($user->updated_at))/3600, 1) <= 24 ? (date('Y-m-d H:i:s',$last_que[2])==$user->updated_at ? true : false) : false ;

                if($link_expire)
                {                   
                    if($user->email_verify)
                    {
                        $data=array('flag'=>true,'msg'=>'Email has already verified','status'=>2);
                    } else {                        
                        $user->email_verify=1;
                        $user->save();

                        $data=array('flag'=>true,'msg'=>'Email is successfully verified !','status'=>1);
                    }
                } else {
                    $data=array('flag'=>false,'msg'=>'Email Verification Link Expired !','status'=>3,'access_tkn'=>$last_que[0]);
                }
            } else {
                $data=array('flag'=>false,'msg'=>'Invalid verification link','status'=>4);
            }           
        }        
        

        return view('verification',$data);
    }

    public function check_user_and_validate($request)
    {
        $data=array('status'=>false,'msg'=>'Data not found');

        $user=User_model::select('users.*','auth_user.auth_id')
                        ->leftjoin('auth_user', 'users.user_id', '=', 'auth_user.user_id')
                        ->where(function ($query) use ($request) {
                            if (!empty($request['email'])) $query->where('users.username', $request['email']);
                            if (!empty($request['user_role'])) $query->where('auth_user.user_role',$request['user_role']);
                            if (!empty($request['password'])) $query->where('users.password',sha1($request['password'].'appcart systemts pvt ltd'));
                          })->first();

                   
                        if((!empty($request['email']) && !empty($request['user_role'])))
                        {
                            if(!empty($user))
                            {
                                $data=array('status'=>true,'msg'=>'User exists','user_data'=>$user->toArray());
                            }else{
                                $data=array('status'=>false,'msg'=>'User not exists');
                            }
                        } else if(!empty($request['email']) && !empty($request['password'])) {
                            if(!empty($user))
                            {
                                $data=array('status'=>true,'msg'=>'User validate','user_data'=>$user->toArray());
                            }else{
                                $data=array('status'=>false,'msg'=>'User not validate');
                            }
                        }

                    return $data;
    }

   
    public function register(Request $request)
    {

        $data=array('status'=>false,'msg'=>'Data not found');

        if($request['email'] && $request['password'] && $request['user_role'] && $request['name'])
         {
            
            $fullname=explode(" ",$request['name']);
            $request['first_name']=count($fullname)>=1 ? $fullname[0] : '' ;
            $request['last_name']=count($fullname)>1 ? $fullname[1] : '' ;

            $request_data=$request->all();
            $check_user_exists=$this->check_user_and_validate(array('email'=>$request_data['email'],'user_role'=>$request_data['user_role']));
           
            if(!$check_user_exists['status'])
            {

                if(($request['user_role']==2 && $request['shop_name']) || $request['user_role']==3)
                {
                    $shop_exists=Shops_model::where('shop_name',$request['shop_name'])->first();
                    if(empty($shop_exists))
                    {              
                       
                                     

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
                        'province' => $request['province'],
                        'city' => $request['city'],
                        'birth_date' => $request['birth_date'],
                        'gender' => $request['gender'],
                        'university' => $request['university'],
                        'token' => $gen_token,
                        'created_at' =>  date('Y-m-d H:i:s'),
                        'updated_at' =>  date('Y-m-d H:i:s')
                    ])->user_id;

                    $user=array(
                        'user_id' => $create_user,
                        'user_role' => $request['user_role']
                    );

                } else {
                    $user=$user->toArray();
                } 


                // ******************************************** Shop Registration **********************************************

                if($request['shop_name'])
                {

                    $create_shop=Shops_model::create([                        
                        'shop_cat_id' => $request['shop_cat_id'],
                        'owner_id' => $user['user_id'],
                        'shop_name' => $request['shop_name'],                   
                        'city' => $request['shop_city'] ? $request['shop_city'] : "",                   
                        'country' => $request['shop_country'] ? $request['shop_country'] : "",  
                        'province' => $request['shop_province'] ? $request['shop_province'] : "",                  
                        'shop_address' => $request['shop_address'] ? $request['shop_address'] : "",                   
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                        ])->shop_id;
    
                        if($create_shop)
                        {
                            $shop_gen_id='sh_'.base64_encode($user['user_id'])."_" .base64_encode($create_shop);
                            $shop=Shops_model::where('shop_id',$create_shop)->first();
                            $shop->shop_gen_id=$shop_gen_id;
                            $shop->save();
                        }

                }
               

            //    ***************************************** Autherisation generate login token ***********************************************
               

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
                        
                        $details = [
                            'title' => 'You have successfully registered on xsent.' ,
                            'body' => 'Below is your username.',
                            'username' => $request['email']
                            // 'password' => $request['password']
                        ];
                       
                        $email_response=\Mail::to($request['email'])->send(new \App\Mail\SendMail($details));

                        $data=array('status'=>true,'msg'=>'Registered successfully. Verification link sent to email, please verify and login.','token'=>$gen_token,'user_role'=> (int)$request['user_role']);
                        if($request['user_role']==2)
                        {
                            if($request['user_role']==2)
                            {
                                $data['shop_details'][]=Shops_model::select('shops.*','users.first_name','users.last_name')
                                ->leftjoin('users', 'shops.owner_id', '=', 'users.user_id')
                                ->where('owner_id',$user['user_id'])
                                ->first()->toArray();
                            }     
                            // if($user_role==5)
                            // {
                            //     $data['shop_details']=Shopkeepers_model::select('shops.*')->leftjoin('shops', 'shopkeepers.shop_id', '=', 'shops.shop_id')->where('salesperson_id',$user_validate['user_data']['user_id'])->first();
                            // }
                        }

                    } else {                        
                        $data=array('status'=>false,'msg'=>'Something went wrong');
                    }

                } else {
                    $data=array('status'=>false,'msg'=>'Shop already exists');
                }

                } else {
                        
                    $data=array('status'=>false,'msg'=>'Please add shop name');
            }


            } else {
                $data=array('status'=>false,'msg'=>'User already exists');
            }

           
         }

         echo json_encode($data);   

    }

    public function login(Request $request)
    {

        $data=array('status'=>false,'msg'=>'Data not found','first_name'=>'','last_name'=>'','shop_details'=>array());

        if($request['email'] && $request['password'] && $request['app_type'])
        {
     
            $request_data=$request->all();
            $user_validate=$this->check_user_and_validate(array('email'=>$request_data['email'],'password'=>$request_data['password']));
           
       
                if($user_validate['status'])
                {

                    $url_explode=explode('/',URL::to('/'));
                   
                    if($user_validate['user_data']['email_verify']==1 || in_array("localhost", $url_explode))
                    {

                        if($request['app_type']=="parent")
                        {
                            if($user_validate['user_data']['user_role']==2){       //owner will login as a parent
                                $user_validate['user_data']['user_role']==3;
                            }
                        } 
                        // else {  //else type will be owner
                        //     if($user_validate['user_data']['user_role']==3){       //parent will login as a owner
                        //         $user_validate['user_data']['user_role']==2;
                        //     }
                        // }


                        $valid_app=($request['app_type']=="parent" && $user_validate['user_data']['user_role']!=5) ? true : ($request['app_type']=="shop" && $user_validate['user_data']['user_role']!=4 && $user_validate['user_data']['user_role']!=3 ? true : false);
                                        
                        $gen_token=sha1(mt_rand(11111,99999).date('Y-m-d H:i:s'));

                        $auth_user=Auth_users::where('user_id',$user_validate['user_data']['user_id'])->where('user_role',$user_validate['user_data']['user_role'])->first();
                    
                        if($valid_app)
                        {                

                        if(!empty($auth_user))
                        {
                            // $user_role=$auth_user->user_role;
                            $user_role=$user_validate['user_data']['user_role'];
                                                
                            $auth_user->users_token=$gen_token;
                            $auth_user->fcm_token=$request['fcm_token'];                        
                            $auth_user->updated_at= date('Y-m-d H:i:s');
                            $auth_user->save();                    
                            
                        } else {
                            $user_role=$user_validate['user_data']['user_role'];

                            $create_auth_user = Auth_users::create([
                                'user_id' => $user_validate['user_data']['user_id'],
                                'user_role' => $user_validate['user_data']['user_role'],
                                'fcm_token' => $request['fcm_token'],
                                'users_token' => $gen_token,
                                'created_at' =>  date('Y-m-d H:i:s'),
                                'updated_at' =>  date('Y-m-d H:i:s')
                            ])->auth_id;
                        }

                        $data=array('status'=>true,'msg'=>'Login successful','token'=>$gen_token,
                                    'first_name'=>$user_validate['user_data']['first_name'],
                                    'last_name'=>$user_validate['user_data']['last_name'],
                                    'user_role'=> $user_role,
                                    'shop_details'=>array());

                        if($user_role==2 || $user_role==5)
                        {
                            if($user_role==2)
                            {
                                $data['shop_details'][]=Shops_model::select('shops.*','users.first_name','users.last_name')
                                                        ->leftjoin('users', 'shops.owner_id', '=', 'users.user_id')
                                                        ->where('owner_id',$user_validate['user_data']['user_id'])
                                                        ->first()->toArray();
                            } 

                            if($user_role==5)
                            {
                                $data['shop_details'][]=Shopkeepers_model::select('shops.*','users.first_name','users.last_name')
                                                        ->leftjoin('shops', 'shopkeepers.owner_id', '=', 'shops.owner_id')
                                                        ->leftjoin('users', 'shops.owner_id', '=', 'users.user_id')
                                                        ->where('shopkeepers.salesperson_id',$user_validate['user_data']['user_id'])
                                                        ->first()->toArray();
                            }
                        }
                    } else {

                        $data=array('status'=>false,'msg'=>'Invalid credentials','first_name'=>'','last_name'=>'','shop_details'=>array());
                    }
                } else {

                    $updated_date=date('Y-m-d H:i:s');
                  
                    $red_url=URL::to('/api/verify-email?access_tkn='.$user_validate['user_data']['token'].'_xsent_'.strtotime($updated_date));
    
                          $details = [
                            'title' => 'Click on verification link to verify email',
                            'body' => $red_url
                        ];

                        $user= User_model::select('users.*')->where('user_id',$user_validate['user_data']['user_id'])->first(); 
                        $user->updated_at=$updated_date;
                        $user->save();
                       
                        $email_response=\Mail::to($user_validate['user_data']['email'])->send(new \App\Mail\SendMail($details));
    
                        

                    $data=array('status'=>false,'msg'=>'Please verify email','first_name'=>'','last_name'=>'','shop_details'=>array());
                }

                } else {
                    $data=array('status'=>false,'msg'=>'Invalid credentials','first_name'=>'','last_name'=>'','shop_details'=>array());
                }
                
            } 

            echo json_encode($data);
    }

  
    public function sendnotification()
    {       

    //    $notify_msg= $this->send_notification(
    //         array(
    //             'title'=>'title',
    //             'msg'=>'message',
    //             'body'=>'body',
    //             'to'=>'d8t5Ge8JQXmoCFUtVTn-aI:APA91bHheCEdDU9mMoVO8uU5sbgHO6exGy1MItlTqhN2FjrP1EUGgkX9Mwx2qDWZJYhhIyqL-3b5D7YetOZ_lAUpp5t1h6vQ-qVSCQO-yDyuL9ARGieJ566C471WpPMjckNNIpjYgWqe'
    //         )
    //     );

        // echo "<pre>";
        // print_r(array_filter(array_column($shopkeepers,'fcm_token')));
        // exit;
      
    }

    public function logout(Request $request)
    {
        $data=array('status'=>false,'msg'=>'Data not found');

        if($request['token'])
        {

            $auth_user=Auth_users::where('users_token',$request['token'])->first();
                  
            if(!empty($auth_user))
            {                                    
                $auth_user->users_token='';
                $auth_user->updated_at= date('Y-m-d H:i:s');
                $auth_user->save();                    
                
                $data=array('status'=>true,'msg'=>'Logout successfully');

            } else {
                $data=array('status'=>true,'msg'=>'Logout successfully');
                // $data=array('status'=>false,'msg'=>'User not found');
            }

        }

        echo json_encode($data);
    }

    public function verify_mobile(Request $request)
    {
        $data=array('status'=>false,'msg'=>'Data not found');

        if($request['contact_no'])
        { 
            $gen_otp=mt_rand(111111,999999);  
            
            $data=array('contact_no'=>'+971'.$request['contact_no'],'msg'=>$gen_otp.' is your Xsent verification code.');

             $response=$this->send_otp($data); 

            $data=array('status'=>true,'msg'=>'Otp sent successfully','otp'=>$gen_otp,'response'=>$response);
        }

        echo json_encode($data); 
    }
   

    public function forgot_password(Request $request)
    {
        $data=array('status'=>false,'msg'=>'Data not found');

        if($request['email'])
        {            
            $check_user_exists= User_model::where('email',$request['email'])->first(); 
            if(!empty($check_user_exists))
            {
                $check_user_exists=$check_user_exists->toArray();
                
                $details = [
                    'title' => 'Forgot Password Email',
                    'body' => 'Your password for xsent is '.$check_user_exists['passphrase']
                ];
               
                $email_response=\Mail::to($request['email'])->send(new \App\Mail\SendMail($details));

                $data=array('status'=>true,'msg'=>'Password is sent to your email id','email_response'=>$email_response);

            } else {
                $data=array('status'=>false,'msg'=>'User not found');
            }
        }

        echo json_encode($data); 
    }

    
    public function getall_province(Request $request)
    {                       
            $province=Province_model::get()->toArray();
           
            $data=array('status'=>true,'msg'=>'Province data','province'=>$province);
     
        echo json_encode($data); 
    }

    public function getall_shop_categories(Request $request)
    {                       
            $categories=Shop_cat_model::get()->toArray();
           
            $data=array('status'=>true,'msg'=>'Province data','categories'=>$categories);
     
        echo json_encode($data); 
    }

    public function getall_cities_by_province(Request $request)
    {                       
        $data=array('status'=>false,'msg'=>'Data not found','cities'=>array());

        if($request['province_id'])
        { 
            $cities=Cities_model::where('province_id',$request['province_id'])->get()->toArray();
           
            $data=array('status'=>true,'msg'=>'Cities data','cities'=>$cities);
        }
     
        echo json_encode($data); 
    }   


    
function send_otp($data)
{ 
    $endpoint = 'https://api.smsglobal.com/http-api.php';
    $client = new \GuzzleHttp\Client();
   
    $response = $client->request('GET', $endpoint, ['query' => [
        'action' => 'sendsms', 
        'user' => env("SEND_OTP_USER"), 
        'from' => 'ibdaa', 
        'to' => $data['contact_no'], 
        'text' => $data['msg'],
        'password' => env("SEND_OTP_PASS"),
    ]]);

    // url will be: http://my.domain.com/test.php?key1=5&key2=ABC;

    $statusCode = $response->getStatusCode();
    $content = $response->getBody();
    
    return $statusCode;  
}

function send_notification_old($data)
{ 
    $endpoint = 'https://fcm.googleapis.com/fcm/send';
    $client = new \GuzzleHttp\Client();
   
    $response = $client->request('GET', $endpoint, ['query' => [
        'action' => 'sendsms', 
        'user' => env("SEND_OTP_USER"), 
        'from' => 'ibdaa', 
        'to' => $data['contact_no'], 
        'text' => $data['msg'],
        'password' => env("SEND_OTP_PASS"),
    ]]);

    // url will be: http://my.domain.com/test.php?key1=5&key2=ABC;

    $statusCode = $response->getStatusCode();
    $content = $response->getBody();
    
    return $statusCode;  
}


  function show_pdf()
  {
    return view('show_pdf');
    
  }
  
  function send_notification($request)
    {    
        //    $msg = urlencode($request['msg']);
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
    
}
