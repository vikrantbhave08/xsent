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