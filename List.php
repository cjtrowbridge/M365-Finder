<?php

switch($_GET['location']){
  case 'Soma':
    ListScooters('37.7782488','-122.405501');
    break;
  case 'Castro':
    ListScooters('7.7608221','-122.4350248');
    break;
  case 'Mission':
    ListScooters('37.7596636','-122.4149094');
    break;
  case 'Berkeley':
    ListScooters('37.8714545','-122.2602743');
    break;
  case 'Fremont':
    ListScooters('37.5444461','-121.9881122');
    break;
  case 'Lake Merritt':
    ListScooters('37.8004072','-122.2575443');
    break;
  case 'Mountain View':
    ListScooters('37.3862282','-122.084398');
    break;
  case 'Palo Alto':
    ListScooters('37.4417158','-122.1431246');
    break;
  case 'San Jose':
    ListScooters('37.3380498','-121.8864084');
    break;
  default:
    die('Invalid Selection');
}

function ListScooters($Latitude, $Longitude){
  
}
