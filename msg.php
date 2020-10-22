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
    if(isset($_POST['msgBody'])){
        $to = $_POST['user_to'];
        $msgBdy = $_POST['msgBody'];
        if(!empty($msgBdy)){
            $msgBdy = str_replace("'", "&#39;", $msgBdy);
            mysqli_query($conn, "INSERT INTO `inner_chat`(`user_from`, `user_to`, `message`) VALUES ('$lvOnline','$to','$msgBdy')");
        }
    } else if(isset($_POST['contM'])){
        $to = $_POST['user_to'];
        $from = $_POST['user_from'];
        $msgBdy = $_POST['body'];
        if(!empty($msgBdy)){
            $msgBdy = str_replace("'", "&#39;", $msgBdy);
            mysqli_query($conn, "INSERT INTO `inner_chat`(`user_from`, `user_to`, `message`) VALUES ('$from','$to','$msgBdy')");
        }
    }else if(isset($_GET['allM'])){
        $u2 = $lvMsg->friends($lvOnline);                            
        echo $lvMsg->recentMsg($lvOnline, $u2);
        mysqli_query($conn, "UPDATE `inner_chat` SET `seen_status`='yes' WHERE `user_to`='$lvOnline' ");
    } else if(isset($_POST['delID'])){
        $id = $_POST['delID'];
        mysqli_query($conn, "UPDATE `inner_chat` SET `deleted`='yes' WHERE `id`='$id' ");
    } else if(isset($_GET['convos'])){
        $other = $_GET['convos'];
        echo $lvMsg->getConvos($other, $lvOnline);
        mysqli_query($conn, "UPDATE `inner_chat` SET `seen_status`='yes', `received_status`='yes' WHERE `user_to`='$lvOnline' AND `user_from`='$other' ");
        ?>
            <div id="conL"></div>
        <?php
    }
?>