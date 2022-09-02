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

        // $curl = curl_init();   
        // curl_setopt_array($curl, array(
        // CURLOPT_URL => 'https://api.smsglobal.com/http-api.php?action=sendsms&user=0tb1pd88&password=inyJdu5t&from=ibdaa&to='.$data['contact_no'].'&text='.$data['msg'],
        // CURLOPT_RETURNTRANSFER => true,
        // CURLOPT_ENCODING => '', 
        // CURLOPT_MAXREDIRS => 10,
        // CURLOPT_TIMEOUT => 0,
        // CURLOPT_FOLLOWLOCATION => true,
        // CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        // CURLOPT_CUSTOMREQUEST => 'GET',
        // ));
        // $response = curl_exec($curl);

        // curl_close($curl);
        // return $response;   
}

function admin_user1()
{ 
    echo "dfgdf";

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