<?php
include("dbconfig.php");
$con = mysqli_connect($host,$username,$password,$dbname);

$sql_user_id = "select user_id, username from 2022S_CPS3961_01.Users";
$result_user_id = mysqli_query($con, $sql);
/*

if (mysqli_num_rows($result_user_id) > 0) {
    echo "<br>Select a Source: <select name='User ' id='source'>
        <option value=''></option>";
    while($row = mysqli_fetch_array($result)) {
        $source = $row["name"];
        $source_id = $row["id"];
        if($source != "") {                
            echo "<option value='$source_id'>$source</option>";
            }
    }
    echo "</select>";
    */

?>