<?php

/*
  Make up random credentials
*/

function getGUID(){
    if (function_exists('com_create_guid')){
        return com_create_guid();
    }
    else {
        mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
        $charid = strtoupper(md5(uniqid(rand(), true)));
        $hyphen = chr(45);// "-"
        $uuid = chr(123)// "{"
            .substr($charid, 0, 8).$hyphen
            .substr($charid, 8, 4).$hyphen
            .substr($charid,12, 4).$hyphen
            .substr($charid,16, 4).$hyphen
            .substr($charid,20,12)
            .chr(125);// "}"
        return $uuid;
    }
}
$GUID  = getGUID();
$Email = substr(md5(microtime()),rand(0,26),8).'@'.substr(md5(microtime()),rand(0,26),8).'.com';


/*
  Login
*/

$Data = '{"email": "'.$Email.'"}';
$curl = curl_init();
curl_setopt_array($curl, [
  CURLOPT_RETURNTRANSFER => 1,
  CURLOPT_URL => 'https://api.bird.co/user/login',
  CURLOPT_USERAGENT => 'Mozilla/5.0 (iPhone; CPU iPhone OS 11_4_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/11.0 Mobile/15E148 Safari/604.1',
  CURLOPT_POST => 1,
  CURLOPT_POSTFIELDS => array($Data)  
]);
curl_setopt($curl, CURLOPT_HTTPHEADER, array(
    'Platform' => 'ios',
    'Device-id' => $GUID,
    'Content-Type' => 'application/json',
    'Content-Length' => strlen($Data))
);
$Login = curl_exec($curl);
curl_close($curl);

var_dump($Login);
exit;

$Login = json_decode($Login,true);
$Token = $Login['token'];


//https://api.bird.co/bird/nearby?latitude=37.77184&longitude=-122.40910&radius=1000

$curl = curl_init();
curl_setopt_array($curl, [
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_URL => 'https://api.bird.co/bird/nearby?longitude='.$Longitude.'&latitude='.$Latitude.'&radius=1000',
    CURLOPT_USERAGENT => 'Codular Sample cURL Request'
]);
curl_setopt($curl, CURLOPT_HTTPHEADER, array(
    'Location' => '{"latitude":'.$Latitude.',"longitude":'.$Longitude.',"altitude":500,"accuracy":100,"speed":-1,"heading":-1}',
    'App-Version' => '3.0.5',
    'Platform' => 'ios',
    'Authorization' => 'Bird '.$Token,
    'Device-id' => $GUID,
    'Content-Type' => 'application/json',
    'Content-Length' => strlen($Data))
);
$Birds = curl_exec($curl);
curl_close($curl);
var_dump($Birds);
