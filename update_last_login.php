<?php
//updates last_login in the Users table
$sql_update = "UPDATE 2022S_CPS3961_01.Users SET last_login = current_timestamp() where user_id = '$user_id'";
$result_update = mysqli_query($con, $sql_update);

if(!$result_update) {
    die("<br>Something wrong with sql_update. " . mysqli_error($con));
}
?>