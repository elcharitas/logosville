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

    if(isset($_POST['addFriend'])){
        $user_from = $_POST['user_from'];
        $user_to = $_POST['user_to'];
        mysqli_query($conn, "INSERT INTO `requests`(`user_from`,`user_to`) VALUES('$user_from', '$user_to')");
        $lvNotify->sendNotification('add',$user_from,$user_to,$user_from);
    }else if(isset($_POST['cancel'])){
        $user_from = $_POST['user_from'];
        $user_to = $_POST['user_to'];
        mysqli_query($conn, "DELETE FROM `requests` WHERE user_from='$user_from' AND user_to='$user_to'");
    } else if(isset($_POST['decline'])){
        $user_from = $_POST['user_from'];
        $user_to = $_POST['user_to'];
        mysqli_query($conn, "DELETE FROM `requests` WHERE user_from='$user_from' AND user_to='$user_to'");
    } else if(isset($_POST['accept'])){
        $user_from = $_POST['user_from'];
        $user_to = $_POST['user_to'];
        mysqli_query($conn, "DELETE FROM `requests` WHERE user_from='$user_from' AND user_to='$user_to'");
        mysqli_query($conn, "INSERT INTO `friends`(`user_one`,`user_two`) VALUES('$user_from', '$user_to')");
        $lvNotify->sendNotification('accept',$user_to,$user_from,$user_to);
    }else if(isset($_POST['unfriend'])){
        $user_from = $_POST['user_from'];
        $user_to = $_POST['user_to'];
        mysqli_query($conn, "DELETE FROM `friends` WHERE (user_one='$user_from' AND user_two='$user_to') OR (user_two='$user_from' AND user_one='$user_to')");
    }
?>