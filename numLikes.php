<?php
    session_start();
    include("dbh.inc.php");
    $lvOnline = $_SESSION['lvOnline'];
    require("classes/User.php");
    require("classes/Posts.php");
    require("classes/Notifications.php");
    $lvUser = new User($conn,$lvOnline);
    $lvPosts = new Posts($conn, $lvOnline);
    $lvNotify = new Notifications($conn, $lvOnline);
    if(isset($_GET['id'])){
        $uniqID = $_GET['id'];
        echo $lvPosts->getLikes($uniqID);
    }
    else if(isset($_POST['idL'])){
        $uniqId = $_POST['idL'];
        $by = $_POST["by"];
        $sql = mysqli_query($conn, "SELECT * FROM likes WHERE uniqID='$uniqId' AND username='$by'");
        if(mysqli_num_rows($sql) == 0){
            mysqli_query($conn, "INSERT INTO likes(`uniqID`, `username`) VALUES('$uniqId', '$by')");
            $sql = mysqli_query($conn, "SELECT * FROM posts WHERE uniqID='$uniqId' AND deleted='no'");
            if(mysqli_num_rows($sql) > 0){
                $get = mysqli_fetch_array($sql);
                $com = $get['likes'];
                $nCom = $com + 1;
                echo $nCom;
                mysqli_query($conn, "UPDATE posts SET likes='$nCom' WHERE uniqID='$uniqId'");
                $to = $_POST['to'];
                if($by != $to){ $lvNotify->sendNotification('like', $by, $to, $uniqId); }
            }
        } else {
            mysqli_query($conn, "DELETE FROM likes WHERE uniqID='$uniqId' AND username='$by'");
            $sql = mysqli_query($conn, "SELECT * FROM posts WHERE uniqID='$uniqId' AND deleted='no'");
            if(mysqli_num_rows($sql) > 0){
                $get = mysqli_fetch_array($sql);
                $com = $get['likes'];
                $nCom = $com - 1;
                echo $nCom;
                mysqli_query($conn, "UPDATE posts SET likes='$nCom' WHERE uniqID='$uniqId'");
            }
        }   
    } else if(isset($_POST['idD'])) {
        $uniqID = $_POST['idD'];
        $sql = mysqli_query($conn, "SELECT * FROM posts WHERE uniqID='$uniqID' AND deleted='no'");
        $get = mysqli_fetch_array($sql);
        $imgID = $get['images'];
        mysqli_query($conn, "DELETE FROM images WHERE imageId='$uniqID'");
        mysqli_query($conn, "DELETE FROM posts WHERE uniqID='$uniqID'");
        mysqli_query($conn, "DELETE FROM likes WHERE uniqID='$uniqID'");
        mysqli_query($conn, "DELETE FROM images WHERE imageId='$uniqID'");
        mysqli_query($conn, "DELETE FROM comments WHERE postID='$uniqID'");
        $sqls = mysqli_query($conn, "SELECT * FROM comments WHERE postID='$uniqID'");
        $gets = mysqli_fetch_array($sqls);
        $id = $gets['id'];
        mysqli_query($conn, "DELETE FROM commrep WHERE commID='$id'"); 
    }  
    
?>