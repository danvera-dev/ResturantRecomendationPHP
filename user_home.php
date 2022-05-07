<?php
//Temporay user home, used just to verify the location api worked
echo "<a href='logout.php'>User Logout</a>";
$u_id = $_COOKIE['user_id'];
include('update_cookies.php');

echo "<br>Welcome";
echo "<br>This is the user home";
/*
echo "<br><br>IP: ". $_COOKIE['ip'];
echo "<br>Country: ". $_COOKIE['country'];
echo "<br>State: ". $_COOKIE['state'];
echo "<br>City: ". $_COOKIE['city'];
echo "<br>Zipcode: ". $_COOKIE['zip'];
echo "<br>Latitude: ". $_COOKIE['lat'];
echo "<br>Longitude: ". $_COOKIE['lon'];
*/
echo "<br><a href='edit_preferences.php'>Settings</a>";

echo "<form action='random_pick.php' method='post'>";
echo "<br>Press here to get recommendation based on your preferences: <input type='submit' value ='Submit'></form>";
if($_GET){
    if(isset($_GET['cuis_choice'])) {
        $cuis_choice = $_GET['cuis_choice'];
        echo "Your weighted random cuisine type selection: <b>".$cuis_choice."</b>";
        
    }
}
?>