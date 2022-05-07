<?php
if (!empty($_SERVER['HTTP_CLIENT_IP'])) { 
  $ip = $_SERVER['HTTP_CLIENT_IP']; 
}
elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) { 
  $ip = $_SERVER['HTTP_X_FORWARDED_FOR']; 
}
else { 
  $ip = $_SERVER['REMOTE_ADDR']; 
}
setcookie("ip", $ip, time() + 60*60);
$IPv4 = explode(".",$ip);
if($IPv4[0] == 10 || ($IPv4[0] == 131 && $IPv4[1] == 125)) { //checks if you were at Kean, avoids problem with Kean IP
    setcookie("country", 'US', time() + 60*60);
    setcookie("zip", '07083', time() + 60*60);
    setcookie("lat", '40.6802', time() + 60*60);
    setcookie("lon", '-74.2331', time() + 60*60);
    setcookie("city", 'Union', time() + 60*60);
    setcookie("state", 'NJ', time() + 60*60);
}
else {
    $geo_url = 'http://ip-api.com/json/' . $ip; //retrieves user location inforamtion using their ip
    $geo_file = file_get_contents($geo_url);
    $geo_arr = json_decode($geo_file, true);
    $country = $geo_arr['countryCode'];
    setcookie("country", $country, time() + 60*60);
    $zip = $geo_arr['zip'];
    setcookie("zip", $zip, time() + 60*60);
    $lat = $geo_arr['lat'];
    setcookie("lat", $lat, time() + 60*60);
    $lon = $geo_arr['lon'];
    setcookie("lon", $lon, time() + 60*60);
    $city = $geo_arr['city'];
    setcookie("city", $city, time() + 60*60);
    $state = $geo_arr['region'];
    setcookie("state", $state, time() + 60*60);
}
include("dbconfig.php");
$con = mysqli_connect($host,$username,$password,$dbname);

$check_loc_sql= "select user_id from 2022S_CPS3961_01.User_locations where user_id = '".$_COOKIE['user_id']."'";
$check_loc_result = mysqli_query($con, $check_loc_sql);  

if($check_loc_result) {
    $update_loc_sql = "update 2022S_CPS3961_01.User_locations set ip='".$_COOKIE['ip']."',lat='".$_COOKIE['lat'].
    "',lon='".$_COOKIE['lon']."',country='".$_COOKIE['country']."',state='".$_COOKIE['state']."',city='".$_COOKIE['city'].
    "' where user_id='".$_COOKIE['user_id']."'";
    $update_loc_result = mysqli_query($con, $update_loc_sql);
    if(!$update_loc_result) {
        die('Update was not successful' . mysqli_error($con) );
    }
}
else {
    $insert_loc_sql = "insert into 2022S_CPS3961_01.User_locations 
    values('".$_COOKIE['user_id']."','".$_COOKIE['ip']."','".$_COOKIE['lat']."','".$_COOKIE['lon']."','"
    .$_COOKIE['country']."','".$_COOKIE['state']."','".$_COOKIE['city']."')";
    $insert_loc_result = mysqli_query($con, $insert_loc_sql);  
    if(!$insert_loc_result) {
        die('Insert was not successful'. mysqli_error($con));
    }
}
