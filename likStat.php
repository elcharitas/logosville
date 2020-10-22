<?php
    session_start();
    include("dbh.inc.php");
    $lvOnline = $_SESSION['lvOnline'];
    require("classes/Posts.php");
    $lvPosts = new Posts($conn, $lvOnline);
    $uniqID = $_GET['id'];
    if($lvPosts->getLikeStat($uniqID, $lvOnline)){
        echo '<i class="fa fa-thumbs-down text-danger" title="Unike"></i>';
    } else {
        echo '<i class="fa fa-thumbs-up text-success" title="Like"></i>';
    }
?>