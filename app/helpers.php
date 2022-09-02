<?php


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

?>