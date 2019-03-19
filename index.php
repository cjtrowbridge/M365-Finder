<?php

/*
  Make up random credentials
*/
$GUID  = com_create_guid();
$Email = substr(md5(microtime()),rand(0,26),8).'@'.substr(md5(microtime()),rand(0,26),8).'.com';


/*
  Login
*/

$Data = '{"email": "'.$Email.'"}';
$curl = curl_init();
curl_setopt_array($curl, [
  CURLOPT_RETURNTRANSFER => 1,
  CURLOPT_URL => 'https://api.bird.co/user/login',
  CURLOPT_USERAGENT => '',
  CURLOPT_POST => 1,
  CURLOPT_POSTFIELDS => $Data  
]);
curl_setopt($curl, CURLOPT_HTTPHEADER, array(
    'Platform: ios',
    'Device-id: '.$GUID.',
    'Content-Type: application/json',
    'Content-Length: ' . strlen($Data))
);
$Login = curl_exec($curl);
curl_close($curl);

var_dump($Login);


$Login = json_decode($LOgin,true);
$Token = $Login['token'];


//https://api.bird.co/bird/nearby?latitude=37.77184&longitude=-122.40910&radius=1000

$curl = curl_init();
curl_setopt_array($curl, [
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_URL => 'https://api.bird.co/bird/nearby?longitude='.$Longitude.'&latitude='.$Latitude.'&radius=1000',
    CURLOPT_USERAGENT => 'Codular Sample cURL Request'
]);
curl_setopt($curl, CURLOPT_HTTPHEADER, array(
    'Location: {"latitude":'.$Latitude.',"longitude":'.$Longitude.',"altitude":500,"accuracy":100,"speed":-1,"heading":-1}',
    'App-Version: 3.0.5',
    'Platform: ios',
    'Authorization: Bird '.$Token.',
    'Device-id: '.$GUID.',
    'Content-Type: application/json',
    'Content-Length: ' . strlen($Data))
);
$Birds = curl_exec($curl);
curl_close($curl);
var_dump($Birds);
