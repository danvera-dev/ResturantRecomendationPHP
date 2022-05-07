<?php
if(!isset($_COOKIE['user_id'])) {
    include('set_offline.php');
    die("Please Login");
}

//$rest_id = $_POST['rest_id'];
$u_id = $_COOKIE['user_id'];
include('update_cookies.php');

$history = $_COOKIE['history'];
$history_arr = json_decode($history, true);

echo "user_id: " . $u_id;
$queries = array();
for($i = 0; $i < count($history_arr); $i++) {
    echo "<br>rest_id ".$i.": ".$history_arr[$i];
    $queries[] = str_replace('"', "", $_COOKIE[$history_arr[$i]]);
}
$hist_ct = count($history_arr);
for($i = 0; $i < count($queries); $i++) {
    echo "<br>rest_id ".$i." query: ".$queries[$i];
}

$history_sql = array_fill(0, 10, NULL);
for($i = 0; $i < count($history_arr); $i++) {
    $history_sql[$i] = $history_arr[$i];
}

include"dbconfig.php";
$con = mysqli_connect($host,$username,$password,$dbname);

$sql_check_hist = "SELECT user_id from 2022S_CPS3961_01.User_history where user_id = $u_id ";
$result_check_hist = mysqli_query($con, $sql_check_hist);
if($result_check_hist){
    if (!mysqli_num_rows($result_check_hist) > 0) {
        echo "<br>NO HISTORY FOR USERID: ".$u_id;
        $sql_insert_hist = "insert into 2022S_CPS3961_01.User_history
            values($u_id, '$history_sql[0]','$history_sql[1]','$history_sql[2]','$history_sql[3]','$history_sql[4]','$history_sql[5]',
            '$history_sql[6]','$history_sql[7]','$history_sql[8]','$history_sql[9]')";
       $result_insert_hist = mysqli_query($con, $sql_insert_hist);
       if($result_insert_hist) {
           echo "could not insert history";
       }
    }
    else {
        $sql_get_history = "select * from 2022S_CPS3961_01.User_history
        where user_id = $u_id ";
        $result_get_history = mysqli_query($con, $sql_get_history);
        $user_hist = array_fill(0, 10, NULL);
        if($result_get_history) {
            if (mysqli_num_rows($result_get_history) > 0) {
                while($row = mysqli_fetch_array($result_get_history)) {
                    $user_hist[0] = $row['rest_1_id'];
                    $user_hist[1] = $row['rest_2_id']; 
                    $user_hist[2] = $row['rest_3_id'];
                    $user_hist[3] = $row['rest_4_id'];
                    $user_hist[4] = $row['rest_5_id'];
                    $user_hist[5] = $row['rest_6_id'];
                    $user_hist[6] = $row['rest_7_id'];
                    $user_hist[7] = $row['rest_8_id'];
                    $user_hist[8] = $row['rest_9_id'];
                    $user_hist[9] = $row['rest_10_id'];
                }                    
            }
        }
        else {
            die("<br>Something wrong with the SQL." . mysqli_error($con));
        }
        for($i = 0; $i < $hist_ct; $i++) {
            for($j = 0; $j < 10; $j++) {
                if(is_null($user_hist[$i])) {
                    break;
                } 
                if($history_arr[$i] == $user_hist[$j]) {
                    $user_hist[$j] = 'x';
                }
            }
        }
        $j = $hist_ct;
        for($i = 0; $j < 10; $i++) {
            if($user_hist != 'x') {
                $history_sql[$j] = $user_hist[$i];
                $j++;
            }
        }
        print_r($history_sql);
        $sql_update_hist = "update 2022S_CPS3961_01.User_history
            set rest_1_id = '$history_sql[0]', rest_2_id = '$history_sql[1]', rest_3_id = '$history_sql[2]',
            rest_4_id = '$history_sql[3]', rest_5_id = '$history_sql[4]', rest_6_id = '$history_sql[5]', rest_7_id = '$history_sql[6]',
            rest_8_id = '$history_sql[7]', rest_9_id = '$history_sql[8]', rest_10_id = '$history_sql[9]'
            where user_id = $u_id";
        $result_update_hist = mysqli_query($con, $sql_update_hist);
        if($result_update_hist) {
            echo "<br>update success"; 
        }
        else {
            die("<br>Something wrong with the SQL." . mysqli_error($con));
        }
    }
}
else {
	die("<br>Something wrong with the SQL." . mysqli_error($con));	
}
?>