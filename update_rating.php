<?php
if(!isset($_COOKIE['user_id'])) {
    include('set_offline.php');
    die("Please Login");
}
echo "<a href='logout.php'>User Logout</a>";

$u_id = $_COOKIE["user_id"];
$cuis_rating = $_POST["cuis_rating"];
$update_success = '';

include"dbconfig.php";
$con = mysqli_connect($host,$username,$password,$dbname);

echo "<br>User_id: ";
echo $u_id;

for($i = 0; $i < count($cuis_rating); $i++) {
    if(!is_numeric($cuis_rating[$i]) or $cuis_rating[$i] < 0 or $cuis_rating[$i] > 3) {
        $update_success = "invalid";
        $host  = $_SERVER['HTTP_HOST'];
        $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        $extra = 'edit_preferences.php';
        header("Location: http://$host$uri/$extra?update_success=".$update_success);
        exit;
        /*
        header("Location: http://obi.kean.edu/~veradan/CPS3962/edit_preferences.php?update_success=".$update_success);
        exit();
        */
    }
}

$sql_rating = "Select French, Chinese, Japanese, Italian, Greek, Spanish, Mediterranean, Lebanese, Moroccan, Turkish, 
    Thai, Indian, Korean, Cajun, American, Mexican, Caribbean, German, Russian, Hungarian from 2022S_CPS3961_01.Cuisine_pref
    where user_id = $u_id";
$result_rating = mysqli_query($con, $sql_rating);
$first_pref = 0;
$og_ratings = array();
if($result_rating) {
    if (mysqli_num_rows($result_rating) > 0) {
        while($row = mysqli_fetch_array($result_rating)) {
            $og_ratings[] = $row['French'];
            $og_ratings[] = $row['Chinese'];
            $og_ratings[] = $row['Japanese'];
            $og_ratings[] = $row['Italian'];
            $og_ratings[] = $row['Greek'];
            $og_ratings[] = $row['Spanish'];
            $og_ratings[] = $row['Mediterranean'];
            $og_ratings[] = $row['Lebanese'];
            $og_ratings[] = $row['Moroccan'];
            $og_ratings[] = $row['Turkish'];
            $og_ratings[] = $row['Thai'];
            $og_ratings[] = $row['Indian'];
            $og_ratings[] = $row['Korean'];
            $og_ratings[] = $row['Cajun'];
            $og_ratings[] = $row['American'];
            $og_ratings[] = $row['Mexican'];
            $og_ratings[] = $row['Caribbean'];
            $og_ratings[] = $row['German'];
            $og_ratings[] = $row['Russian'];
            $og_ratings[] = $row['Hungarian']; 
        }
    }
    else {
        $og_ratings = array_fill(0, 20, 0);
        $first_pref = 1;
    }
}
else {
	die("<br>Something wrong with the SQL." . mysqli_error($con));	
}

if($first_pref = 1){

    $sql_insert_pref = "insert into 2022S_CPS3961_01.Cuisine_pref 
        values($user_id,$cuis_rating[0],$cuis_rating[1],$cuis_rating[2],$cuis_rating[3],$cuis_rating[4],$cuis_rating[5],
        $cuis_rating[6],$cuis_rating[7],$cuis_rating[8],$cuis_rating[9],$cuis_rating[10],$cuis_rating[11],$cuis_rating[12],
        $cuis_rating[13],$cuis_rating[14],$cuis_rating[15],$cuis_rating[16],$cuis_rating[17],$cuis_rating[18],$cuis_rating[19])";
        $result_insert_pref = mysqli_query($con, $sql_insert_pref);
    $result_insert_pref = mysqli_query($con, $sql_insert_pref);    
    if(!$result_insert_pref) {
        die("insert did not work")
    }
}
else {
    $cuis_list = array();
    $sql_cuisine = "SELECT cuisine_type FROM 2022S_CPS3961_01.Cuisine_list";
    $result_cuisine = mysqli_query($con, $sql_cuisine);
    if($result_cuisine) {
        if (mysqli_num_rows($result_cuisine) > 0) {
            while($row = mysqli_fetch_array($result_cuisine)) {
                $cuis_list[] = $row['cuisine_type'];
            }
        }
    }
    else {
        die("<br>Something wrong with the SQL." . mysqli_error($con)); 
    }
    echo "<br>";
    print_r($cuis_list);

    for($i = 0; $i < count($cuis_rating); $i++) {
        if($cuis_rating[$i] != $og_ratings[$i]) {
            $sql_update = "UPDATE 2022S_CPS3961_01.Cuisine_pref
            SET $cuis_list[$i] = $cuis_rating[$i]
            where user_id = $u_id";
            $result_update = mysqli_query($con, $sql_update);
            if(!$result_update){ 
                echo "<br>Something wrong with sql_update. " . mysqli_error($con);
            } 
        }
    }
}

$update_success = "success";
$host  = $_SERVER['HTTP_HOST'];
$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
$extra = 'edit_preferences.php';
header("Location: http://$host$uri/$extra?update_success=".$update_success);
exit;
/*
header("Location: http://obi.kean.edu/~veradan/CPS3962/edit_preferences.php?update_success=".$update_success);
exit();
*/
?>