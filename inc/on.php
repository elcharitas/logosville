<?php
    include("../dbh.inc.php");
    $lvOnline = $_POST['lvOnline'];
    date_default_timezone_set('Africa/Lagos');
    $date_time_now = date("Y-m-d H:i:s");
    $sql = mysqli_query($conn, "UPDATE users SET last_seen='$date_time_now' WHERE username='$lvOnline'");
?>