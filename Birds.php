<?php

class Birds{

  private $Email    = false;
  private $Token    = false;
  private $DeviceID = false;
  
  function __construct($AuthToken = false){
    if($AuthToken != false){
      $this->Token = $AuthToken;
    }
  }
  
  /*
    Return the current auth token or get one and then return it
  */
  function Token(){
    if($this->Token==false){
      $this->Token = $this->getAuthToken()['token'];
    }
    return $this->Token;
  }
  
  /*
    Return the current auth token or get one and then return it
  */
  function Email(){
    if($this->Email==false){
      $this->Email = substr(md5(microtime()),rand(0,26),8).'@'.substr(md5(microtime()),rand(0,26),8).'.com';
    }
    return $this->Email;
  }
  
  /*
    Return the current device id or create one one and then return it
  */
  function DeviceID(){
    if($this->DeviceID==false){
       if (function_exists('com_create_guid')){
          $this->DeviceID = com_create_guid();
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
          $this->DeviceID = $uuid;
      }
    }
    return $this->DeviceID;
  }
  
  /*
    Connect to the api and get a new auth token
  */
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
      CURLOPT_POSTFIELDS => "{\"email\": \"".($this->Email())."\"}",
      CURLOPT_HTTPHEADER => array(
        "cache-control: no-cache",
        "content-type: application/json",
        "device-id: ".($this->DeviceID()),
        "platform: ios"
      ),
    ));
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    if ($err) {
      die("cURL Error while getting auth token #:" . $err);
    }
    $response = json_decode($response,true);
    return $response;
  }

  /*
    Get all nearby scooters
  */
  function getNearbyScooters($Latitude = '37.7582503', $Longitude = '-122.5541942'){
    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => "https://api.bird.co/bird/nearby?radius=1000",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "GET",
      CURLOPT_HTTPHEADER => array(
        "app-version: 3.0.5",
        "authorization: Bird ".($this->Token()),
        "cache-control: no-cache",
        "content-type: application/json",
        "device-id: ".($this->DeviceID()),
        "location: {\"latitude\":".$Latitude.",\"longitude\":".$Longitude.",\"altitude\":500,\"accuracy\":100,\"speed\":-1,\"heading\":-1}",
      ),
    ));
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    if ($err) {
      echo "cURL Error #:" . $err;
    }
    $response = json_decode($response,true);
    return $response;
  }
  
  /*
    Get details about a specific scooter by its id code
  */
  function getScooterDetail($ScooterID){
    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => "https://api.bird.co/bird/chirp",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "PUT",
      CURLOPT_POSTFIELDS => "{\"alarm\": false,\"bird_id\": \"".$ScooterID."\"}",
      CURLOPT_HTTPHEADER => array(
        "app-version: 3.0.5",
        "authorization: Bird ".($this->Token()),
        "cache-control: no-cache",
        "content-type: application/json",
        "device-id: ".($this->DeviceID)
      ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    if ($err) {
      die("cURL Error while fetching device details #:" . $err);
    }
    $response = json_decode($response,true);
    return $response;
  }
}
