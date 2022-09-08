<?php

namespace App\Http\Controllers\Mobile_api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// use App\Models\User_model;
// use App\Models\Auth_users;

class Auth_controller extends Controller
{
    //
    public function __construct()
    {       
       
    }

    public function greeting(Request $request)
    {
        echo "greeting";
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
                                $data=array('status'=>true,'msg'=>'User exists');
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

        if($request['email'] && $request['password'] && $request['user_role'])
         {
            $request_data=$request->all();
            $check_user_exists=$this->check_user_and_validate(array('email'=>$request_data['email'],'user_role'=>$request_data['user_role']));
           
            if(!$check_user_exists['status'])
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
                        $data=array('status'=>true,'msg'=>'User registered successfully','token'=>$gen_token,'user_role'=> $request['user_role']);
                    } else {                        
                        $data=array('status'=>false,'msg'=>'Something went wrong');
                    }


            } else {
                $data=array('status'=>false,'msg'=>'User already exists');
            }

           
         }

         echo json_encode($data);   

    }

    public function login(Request $request)
    {

        if($request['email'] && $request['password'] && $request['user_role'])
        {
     
            $request_data=$request->all();
        $user_validate=$this->check_user_and_validate(array('email'=>$request_data['email'],'password'=>$request_data['password']));
           
        // echo "<pre>";
        // print_r($user_validate);
        // exit;
                if($user_validate['status'])
                {
                    $gen_token=sha1(mt_rand(11111,99999).date('Y-m-d H:i:s'));

                    $auth_user=Auth_users::where('user_id',$user_validate['user_data']['user_id'])->where('user_role',$request['user_role'])->first();
                  

                    if(!empty($auth_user))
                    {

                        $user_role=$auth_user->user_role;
                                            
                        $auth_user->users_token=$gen_token;
                        $auth_user->fcm_token=$request['fcm_token'];                        
                        $auth_user->updated_at= date('Y-m-d H:i:s');
                        $auth_user->save();                    
                        
                    } else {

                        // $user_role=$auth_user->user_role;

                        $create_auth_user = Auth_users::create([
                            'user_id' => $user_validate['user_data']['user_id'],
                            'user_role' => $request['user_role'],
                            'fcm_token' => $request['fcm_token'],
                            'users_token' => $gen_token,
                            'created_at' =>  date('Y-m-d H:i:s'),
                            'updated_at' =>  date('Y-m-d H:i:s')
                        ])->auth_id;

                    }

                    $data=array('status'=>true,'msg'=>'Login successful','token'=>$gen_token,'user_role'=> $user_role);

                } else {
                    $data=array('status'=>false,'msg'=>'Invalid credentials');
                }
                
            } else {
                
                $data=array('status'=>false,'msg'=>'Data not found');
                
            }

            echo json_encode($data);
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

                $data=array('status'=>false,'msg'=>'User not found');

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
            
            $data=array('contact_no'=>$request['contact_no'],'msg'=>$gen_otp.' is your Xsent verification code.');

             $response=send_otp($data); 

            $data=array('status'=>true,'msg'=>'Otp sent successfully','otp'=>$gen_otp,'response'=>$response);
        }

        echo json_encode($data); 
    }

    public function forgot_password(Request $request)
    {
        $data=array('status'=>false,'msg'=>'Data not found');

        if($request['email'])
        {
            
            // $check_user_exists=$this->check_user_and_validate(array('email'=>$request_data['email']));
            // if($check_user_exists['status'])
            // {

            // }

            $details = [
                'title' => 'Mail from ItSolutionStuff.com',
                'body' => 'This is for testing email using smtp'
            ];
           
            \Mail::to('suraj@appcartsystems.com')->send(new \App\Mail\SendMail($details));
           
            dd("Email is Sent.");

            // $gen_otp=mt_rand(111111,999999);              
            // $data=array('contact_no'=>'+919075554309','msg'=>$gen_otp.' is your Xsent verification code.');
            //  $response=send_otp($data); 
            // $data=array('status'=>true,'msg'=>'Otp sent successfully','otp'=>$gen_otp,'response'=>$response);
        }

        echo json_encode($data); 
    }

   
    public function send_notification($title='',$msg='',$body='',$to='')
    {    

           $msg = urlencode($msg);
            $data = array(
                'title'=>$title,
                'sound' => "default",
                'msg'=>$msg,
                'data'=>'Data',
                'body'=>$body,
                'color' => "#79bc64"
            );
        // if($img){
        //     $data["image"] = $img;
        //     $data["style"] = "picture";
        //     $data["picture"] = $img;
        // }
        $fields = array(           
            'to'=>$to,
            'notification'=>$data,
            // 'data'=>'Datapayload',
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
        //  print_r($result); 
      
    }
}