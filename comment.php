<?php
    session_start();
    include("dbh.inc.php");
    require("classes/Notifications.php");
    require("classes/User.php");
    $lvOnline = $_SESSION['lvOnline'];
    $lvNotify = new Notifications($conn, $lvOnline);
    $lvUser = new User($conn, $lvOnline);
    if(isset($_POST['cmtText'])){
        $cmtText = $_POST['cmtText'];
        $cmtText = str_replace("'", "&#39;", $cmtText);
        $by = $_POST["by"];
        $to = $_POST['to'];
        $uniqId = $_POST["uniqId"];
        if(!empty($cmtText)){
            mysqli_query($conn, "INSERT INTO comments(`postID`, `body`, `posted_by`) VALUES('$uniqId', '$cmtText', '$by')");
            $sql = mysqli_query($conn, "SELECT * FROM posts WHERE uniqID='$uniqId' AND deleted='no'");
            if(mysqli_num_rows($sql) > 0){
                $get = mysqli_fetch_array($sql);
                $com = $get['comments'];
                $nCom = $com + 1;
                echo $nCom;
                mysqli_query($conn, "UPDATE posts SET comments='$nCom' WHERE uniqID='$uniqId'");
                if($by != $to){ $lvNotify->sendNotification('comment', $by, $to, $uniqId); }
            }
        } 
    } else if (isset($_POST['reply'])){
        $body = $_POST['textId'];
        $commId = $_POST['commId'];
        mysqli_query($conn, "INSERT INTO commrep(`commID`, `body`, `created_by`) VALUES('$commId', '$body', '$lvOnline')");
    }
?>