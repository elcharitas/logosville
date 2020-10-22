<?php
    session_start();
    include("dbh.inc.php");
    $lvOnline = $_SESSION['lvOnline'];
    require("classes/Posts.php");
    $lvPosts = new Posts($conn, $lvOnline);
    if(isset($_GET['id'])){
        $uniqID = $_GET['id'];
    }
    echo $lvPosts->getComments($uniqID);
?>