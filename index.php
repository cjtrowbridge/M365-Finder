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


function getAuthToken(){
  $curl = curl_init();
  curl_setopt_array($curl, array(
    CURLOPT_URL => "https://api.bird.co/user/login",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => "{\"email\": \"".$Email."\"}",
    CURLOPT_HTTPHEADER => array(
      "cache-control: no-cache",
      "content-type: application/json",
      "device-id: ".getGUID(),
      "platform: ios"
    ),
  ));

  $response = curl_exec($curl);
  $err = curl_error($curl);

  curl_close($curl);

  if ($err) {
    echo "cURL Error #:" . $err;
  } else {
    echo $response;
  }
  return $response;
}
  
  
  

//https://api.bird.co/bird/nearby?latitude=37.77184&longitude=-122.40910&radius=1000

$Latitude  = '37.8038906';
$Longitude = '-122.2644321';

$curl = curl_init();
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); 
curl_setopt_array($curl, [
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_URL => 'https://api.bird.co/bird/nearby?longitude='.$Longitude.'&latitude='.$Latitude.'&radius=1000',
    CURLOPT_USERAGENT => ''
]);
curl_setopt($curl, CURLOPT_HTTPHEADER, array(
  'latitude' => $Latitude,
  'longitude' => $Longitude,
  'altitude' => '500',
  'speed' => '-1',
  'heading' => '-1'
  
  /*
  'Location' => '{"latitude":'.$Latitude.',"longitude":'.$Longitude.',"altitude":500,"accuracy":100,"speed":-1,"heading":-1}',
  'App-Version' => '3.0.5',
  'Platform' => 'ios',
  'Authorization' => 'Bird '.$Token,
  'Device-id' => $GUID,
  'Content-Type' => 'application/json',
  'Content-Length' => strlen($Data))
  */
));
curl_setopt($curl, CURLOPT_VERBOSE, true);
$Birds = curl_exec($curl);
curl_close($curl);   
$Birds = json_decode($Birds,true);
var_dump($Birds);
