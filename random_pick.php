<?php
if(!isset($_COOKIE['user_id'])) {
    include('set_offline.php');
    die("Please Login");
}
$u_id = $_COOKIE["user_id"];
include('update_cookies.php');
include"dbconfig.php";
$con = mysqli_connect($host,$username,$password,$dbname);

$sql_pref = "select * from 2022S_CPS3961_01.Cuisine_pref where user_id = 1";
$result_pref =  mysqli_query($con, $sql_pref);

$cuis_rating = array();
if($result_pref) {
    if (mysqli_num_rows($result_pref) > 0) {
        while($row = mysqli_fetch_array($result_pref)) {
            $cuis_rating[] = $row['French'];
            $cuis_rating[] = $row['Chinese'];
            $cuis_rating[] = $row['Japanese'];
            $cuis_rating[] = $row['Italian'];
            $cuis_rating[] = $row['Greek'];
            $cuis_rating[] = $row['Spanish'];
            $cuis_rating[] = $row['Mediterranean'];
            $cuis_rating[] = $row['Lebanese'];
            $cuis_rating[] = $row['Moroccan'];
            $cuis_rating[] = $row['Turkish'];
            $cuis_rating[] = $row['Thai'];
            $cuis_rating[] = $row['Indian'];
            $cuis_rating[] = $row['Korean'];
            $cuis_rating[] = $row['Cajun'];
            $cuis_rating[] = $row['American'];
            $cuis_rating[] = $row['Mexican'];
            $cuis_rating[] = $row['Caribbean'];
            $cuis_rating[] = $row['German'];
            $cuis_rating[] = $row['Russian'];
            $cuis_rating[] = $row['Hungarian'];
        }
    }
}
else {
    die("<br>Something wrong with the SQL." . mysqli_error($con));
}

$sql_cuis = "select cuisine_type from 2022S_CPS3961_01.Cuisine_list";
$result_cuis =  mysqli_query($con, $sql_cuis);

$cuis_id = array();
if($result_cuis) {
    if (mysqli_num_rows($result_cuis) > 0) {
        while($row = mysqli_fetch_array($result_cuis)) {
            $cuis_id[] = $row['cuisine_type'];
        }
    }
}
else {
    die("<br>Something wrong with the SQL." . mysqli_error($con));
}

$cuis_choice = weighted_random_simple($cuis_id, $cuis_rating);
setcookie("cuis_choice",$u_id,time() + 60*10);

$host  = $_SERVER['HTTP_HOST'];
$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/');
$extra = "user_home.php?cuis_choice=".$cuis_choice;
header("Location: http://$host$uri/$extra");
exit;

//https://theprogrammersfirst.wordpress.com/2020/07/22/generating-random-results-by-weight-in-php/
function weighted_random_simple($values, $weights){
    $count = count($values);
    $i = 0;
    $n = 0;
    $num = mt_rand(1, array_sum($weights));
    while($i < $count){
        $n += $weights[$i];
        if($n >= $num){
            break;
        }
        $i++;
    }
    return $values[$i];
}

?>
