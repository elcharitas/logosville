<?php
    session_start();
    $lvOnline = $_SESSION['lvOnline'];
    include("dbh.inc.php");
    require("classes/User.php");
    require("classes/Friends.php");
    require("classes/Posts.php");
    require("classes/Notifications.php");
    require("classes/Messages.php");
    $lvUser = new User($conn, $lvOnline);
    $lvFriend = new Friends($conn, $lvOnline);
    $lvPosts = new Posts($conn, $lvOnline);
    $lvNotify = new Notifications($conn, $lvOnline);
    $lvMsg = new Messages($conn, $lvOnline);
    if(isset($_GET['notiOwn'])){
        if($lvNotify->getAllNotices($lvOnline) == 0){
            
        } else {
            echo '<span class="badge badge-danger notNUM">'.$lvNotify->getAllNotices($lvOnline).'</span>';
            ?>
                <style>
                    .notL{
                        opacity: 1;
                    }
                </style>
            <?php
        }
    } else if(isset($_GET['reqOwn'])) {
        if($lvFriend->getAllReq($lvOnline) == 0){
            
        } else {
            echo '<span class="badge badge-danger notNUM">'.$lvFriend->getAllReq($lvOnline).'</span>';
            ?>
                <style>
                    .frL{
                        opacity: 1;
                    }
                </style>
            <?php
        }
    } else if(isset($_POST['notiOwn2'])){
        mysqli_query($conn, "UPDATE notifications SET seen='yes' WHERE user_to='$lvOnline'");
        if($lvNotify->getAllNotices($lvOnline) == 0){
            
        } else {
            echo '<span class="badge badge-danger notNUM">'.$lvNotify->getAllNotices($lvOnline).'</span>';
            ?>
                <style>
                    .notL{
                        opacity: 1;
                    }
                </style>
            <?php
        }
    } else if(isset($_POST['marker'])) {
        mysqli_query($conn, "UPDATE notifications SET readStat='yes' WHERE user_to='$lvOnline'");
    } else if(isset($_GET['notBDY'])){
        echo $lvNotify->myNotifications($lvOnline);
    } else if(isset($_POST['markId'])){
        $markId = $_POST['markId'];
        mysqli_query($conn, "UPDATE notifications SET readStat='yes' WHERE id='$markId'");
    } else if(isset($_GET['msgBDY'])){
        $u2 = $lvMsg->friends($lvOnline);                            
        echo $lvMsg->recentMessages($lvOnline, $u2);
    }
    
?>
<style>
    .notNUM{
        float:right;
        position:relative;
        margin-right:-10px;
        margin-top:-5px;
    }
</style>