<?php
$u_id = $_COOKIE["user_id"];
include("dbconfig.php");
$con = mysqli_connect($host,$username,$password,$dbname);

$sql_offline = "UPDATE 2022S_CPS3961_01.Users
    SET is_online = 0
    where user_id = $u_id";
$result_offline = mysqli_query($con, $sql_offline);
if(!$result_offline){ 
    echo "<br>Something wrong with sql_update. " . mysqli_error($con);
    }    
?>