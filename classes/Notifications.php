<?php
class Notifications
{
    public $conn;
    public $user_obj;

    public function __construct($conn, $user_obj)
    {
        $this->conn = $conn;
        $this->user = $user_obj;
    }

    public function timeago($date) {
        date_default_timezone_set('Africa/Lagos');
        $timestamp = strtotime($date);	
        
        $strTime = array("s", "m", "h", "d", "m", "y");
        $length = array("60","60","24","30","12","10");
 
        $currentTime = time();
        if($currentTime >= $timestamp) {
             $diff     = time()- $timestamp;
             for($i = 0; $diff >= $length[$i] && $i < count($length)-1; $i++) {
             $diff = $diff / $length[$i];
             }
 
             $diff = round($diff);
             return $diff . " " . $strTime[$i];
        }
    }

    public function myNotifications($user){
        $sqlss = mysqli_query($this->conn, "SELECT * FROM notifications WHERE user_to='$user' ORDER BY id DESC ");
        if(mysqli_num_rows($sqlss) == 0){
            return "<span class='sULi'>You have no new notification</span>";
        } else {
            while($noti = mysqli_fetch_assoc($sqlss)){
                $id = $noti['id'];
                $from = $noti['user_from'];
                $to = $noti['user_to'];
                $href = $noti['location'];
                $remark = $noti['remark'];
                $read = $noti['readStat'];
                $date = $noti['created_at'];
                $lvUser = new User($this->conn, $to);
                $lvNotify = new Notifications($this->conn, $to);
                $dp = $lvUser->getDp($from);
                $time = $lvNotify->timeago($date);
                ?>
                    <li class="<?php echo (($read == 'no') ? "notiFLiU" : "notiFLiR"); ?>" id="notification<?php echo $id; ?>">
                        <a class="notiFLi" href="<?php echo $href; ?>">
                            <img src="<?php echo $dp; ?>" class="img" style="margin: auto;"><span style="width: 250px;padding: 5px;"><?php echo $remark; ?></span><span style="width: 30px;margin: auto;"><?php echo $time; ?></span>
                        </a>
                    </li>
                    <script>
                        $(document).ready(function() {
                            $("#notification<?php echo $id; ?>").on("click", function(){
                                $.ajax({
                                    url: "/noticeAjax.php",
                                    type: "POST",
                                    data: "markId=<?php echo $id; ?>"
                                });
                            });
                        });
                    </script>
                <?php
            }
        }
    }

    public function getAllNotices($user){
        $notQuery = mysqli_query($this->conn, "SELECT * FROM notifications WHERE user_to='$user' AND seen='no'");
        return mysqli_num_rows($notQuery);
    }

    public function sendNotification($condition, $user_from, $user_to, $id){
        $lvUser = new User($this->conn, $user_from);
        $from = $lvUser->getFullName($user_from);
        $to = $lvUser->getFullName($user_to);
        switch ($condition) {
            case "request":
                $msg = $from . " sent you a friend request";
                $link = '/friends/' . $from;
                break;
            case "accept":
                $msg = $from . " accepted your friend request";
                $link = '/u/' . $user_from;
                break;
            case "add":
                $msg = $from . " sent you a friend request";
                $link = '/u/' . $user_from;
                break;
            case "comment":
                $msg = $from . " commented on your post";
                $link = '/posts/' . $id;
                break;
            case "publish":
                $msg = $from . " published a post";
                $link = '/posts/' . $id;
                break;
            case "profile post":
                if($user_from != $user_to){
                    $msg = $from . " published a post on your status";
                    $link = '/posts/' . $id;
                }
                break;
            case "like":
                $msg = $from . " likes your post";
                $link = '/posts/' . $id;
                break;
        }
        mysqli_query($this->conn, "INSERT INTO `notifications`(`user_from`, `user_to`, `remark`, `location`) VALUES('$user_from','$user_to','$msg','$link')" );
    }


}
