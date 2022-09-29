<?php

namespace Illuminate\Support\Facades;

use Laravel\Ui\UiServiceProvider;
use RuntimeException;
Use Session;

use App\Models\User_model;
use App\Models\Auth_users;


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

function send_notification($data)
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


  function send_notification_new($title='title',$msg='message',$body='body',$to='')
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
       
        $fields = array(           
            'to'=>$to,
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


function admin_user1()
{    
    // echo session('admin_token');
    // $user = User_model::select('users.*','auth_user.auth_id','auth_user.users_token')
    //     ->leftjoin('auth_user', 'users.user_id', '=', 'auth_user.user_id')
    //     ->where('auth_user.users_token', session('admin_token'))
    //     ->first();
    //     if(!empty($user))
    //     {
    //         $user = $user->toArray();       
    //     }

    //     return $user;
}

?>