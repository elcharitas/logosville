<?php
    session_start();
    include("dbh.inc.php");
    $lvOnline = $_SESSION['lvOnline'];
    require("classes/Friends.php");
    $lvFriends = new Friends($conn, $lvOnline);
    echo $lvFriends->numActiveFriends($lvOnline);
?>