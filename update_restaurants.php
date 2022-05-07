<?php
if(!isset($_COOKIE['user_id'])) {
    include('set_offline.php');
    die("Please Login");
}

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
$query_ct = count($queries);

$history_sql = array_fill(0, 10, NULL);
for($i = 0; $i < count($history_arr); $i++) {
    $history_sql[$i] = $history_arr[$i];
}

include"dbconfig.php";
$con = mysqli_connect($host,$username,$password,$dbname);
for($i = 0; $i < $hist_ct; $i++) {
    $sql_check_rest = "SELECT * from 2022S_CPS3961_01.Restaurants where rest_id = '$history_arr[$i]' ";
    $result_check_rest = mysqli_query($con, $sql_check_rest);
    if($result_check_rest) {
        if (mysqli_num_rows($result_check_rest) > 0) {
            $sql_update_rest = "UPDATE 2022S_CPS3961_01.Restaurants 
            set last_searched = current_timestamp(), total_searchs = (total_searchs + 1)
            where rest_id = '$history_arr[$i]'";
            $result_update_rest = mysqli_query($con, $sql_update_rest);
            if($result_update_rest) {
                echo "<br>update rest success";
                $db_queries = array_fill(0, 5, NULL);
                $sql_get_queries = "SELECT * from 2022S_CPS3961_01.Rest_queries where rest_id = '$history_arr[$i]'";
                $result_get_queries = mysqli_query($con, $sql_get_queries);
                if($result_get_queries){
                    if (mysqli_num_rows($result_get_queries) > 0) {
                        while($row = mysqli_fetch_array($result_get_queries)) {
                            $db_queries[0] = $row['query1'];
                            $db_queries[1] = $row['query2'];
                            $db_queries[2] = $row['query3'];
                            $db_queries[3] = $row['query4'];
                            $db_queries[4] = $row['query5'];
                        }
                    }
                }
                else {
                    die("<br>Something wrong with the SQL." . mysqli_error($con));
                }
                $q = 0;
                for($j = $query_ct; $j < 5; $j++) {
                    if(!is_null($db_queries[$q])) {
                        $queries[$j] = $db_queries[$q]; 
                    }
                    else {
                        break;
                    }
                    $q++;
                }
                $sql_update_queries = "UPDATE 2022S_CPS3961_01.Rest_queries 
                    set = query1 = '$queries[0]', set = query2 = '$queries[1]', set = query3 = '$queries[2]',
                    set = query4 = '$queries[3]', set = query5 = '$queries[4]' 
                    where rest_id = '$history_arr[$i]'";
                $result_update_queries = mysqli_query($con, $sql_update_queries);
                if($result_update_queries) {
                    echo "<br>update queries success";
                }
                else{
                    die("<br>Something wrong with the SQL." . mysqli_error($con)); 
                }
            }
        }
        else {
            $sql_insert_rest = "INSERT into 2022S_CPS3961_01.Restaurants
            values('$history_arr[$i]', current_timestamp(), 1)";
            $result_insert_rest = mysqli_query($con, $sql_insert_rest);
            if($result_insert_rest) {
                echo "<br>insert rest success";
                $full_query = array_fill(0, 5, NULL);
                for($j = 0; $j < $query_ct; $j++) {
                    $full_query[$j] = $queries[$j];
                }
                $sql_insert_queries = "INSERT into 2022S_CPS3961_01.Rest_queries
                    values('$history_arr[$i]', '$full_query[0]', '$full_query[1]', '$full_query[2]', 
                    '$full_query[3]', '$full_query[4]')";
                $result_insert_queries = mysqli_query($con, $sql_insert_queries);
                if($result_insert_queries) {
                    echo "<br>insert query success";
                }
                else {
                    die("<br>Something wrong with the SQL." . mysqli_error($con)); 
                }
            } 
        }   
    }
    else {
        die("<br>Something wrong with the SQL." . mysqli_error($con)); 
    }
}
?>