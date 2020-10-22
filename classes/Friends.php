<?php
class Friends
{
    public $conn;
    public $user_obj;

    public function __construct($conn, $user_obj)
    {
        $this->conn = $conn;
        $this->user = $user_obj;
    }

    public function isFriend($user_one, $user_two){
        $sql = mysqli_query($this->conn, "SELECT * FROM friends WHERE (user_one='$user_one' AND user_two='$user_two') OR (user_one='$user_two' AND user_two='$user_one')");
        if(mysqli_num_rows($sql) == 0){
            return false;
        } else {
            return true;
        }
    }

    public function numFriends($user){
        $sql = mysqli_query($this->conn, "SELECT * FROM friends WHERE user_one='$user' OR user_two='$user'");
        if(mysqli_num_rows($sql) == 0){
            return "You have no friend";
        } else {
            return mysqli_num_rows($sql);
        }
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

    public function reqSent($user_to, $user_from){
        $reqQuery = mysqli_query($this->conn, "SELECT * FROM requests WHERE user_to='$user_to' AND user_from='$user_from'");
        if(mysqli_num_rows($reqQuery) == 0){
            return false;
        } else {
            return true;
        }
    }

    public function reqRec($user_to, $user_from){
        $reqQuery = mysqli_query($this->conn, "SELECT * FROM requests WHERE user_from='$user_to' AND user_to='$user_from'");
        if(mysqli_num_rows($reqQuery) == 0){
            return false;
        } else {
            return true;
        }
    }

    public function listFriends($user){
        $sql = mysqli_query($this->conn, "SELECT * FROM friends WHERE user_one='$user' OR user_two='$user'");
        if(mysqli_num_rows($sql) == 0){
            return '<li><div class="sFLi">You have no friend</li></div>';
        } else {
            while($friends = mysqli_fetch_assoc($sql)){
                $user_one = $friends['user_one'];
                $user_two = $friends['user_two'];
                $myFriend = "";
                if($user_one == $user){
                    $myFriend .= $user_two;
                }
                if($user_two == $user){
                    $myFriend .= $user_one;
                }
                $sqls = mysqli_query($this->conn, "SELECT * FROM users WHERE verified='1' AND username='$myFriend' ");
                $theUser = mysqli_fetch_array($sqls);
                $username = $theUser['username'];
                $img = $theUser['dp'];
                $name = ucfirst($theUser['last']) . " " . ucfirst($theUser['first']) . " " . ucfirst($theUser['other']);
                $time = $theUser['last_seen'];
                $newF = new Friends($this->user_obj, $this->conn);
                $date = $newF->timeago($time);
                ?>
                    <li>
                        <div class="sFLi">
                            <div class="sFLI-inner">
                                <a href="/u/<?php echo $username; ?>">
                                    <img src="<?php echo $img; ?>" alt="" class="img">
                                </a>
                            </div>
                            <div class="sFLiW">
                                <a href="/u/<?php echo $username; ?>/">
                                    <div class="sULi"><?php echo $name; ?></div>
                                </a>
                            </div>
                            <div class="sFLiI" style="font-size: 8;">
                                <?php
                                    if(strtotime($time) >= strtotime("-1 minutes")){
                                        ?>
                                            <div class="onlineImg"></div>
                                        <?php
                                    } else {
                                        echo $date;
                                    }
                                ?>
                            </div>
                        </div>
                    </li>
                <?php
            }
        }
    }

    public function numActiveFriends($user){
        $sql = mysqli_query($this->conn, "SELECT * FROM friends WHERE user_one='$user' OR user_two='$user'");
        if(mysqli_num_rows($sql) == 0){
            return '0';
        } else {
            $i = 0;
            while($friends = mysqli_fetch_assoc($sql)){
                $user_one = $friends['user_one'];
                $user_two = $friends['user_two'];
                $myFriend = "";
                if($user_one == $user){
                    $myFriend .= $user_two;
                }
                if($user_two == $user){
                    $myFriend .= $user_one;
                }
                $get_time_query = mysqli_query($this->conn, "SELECT * FROM users WHERE username='$myFriend'");
                while($get_time = mysqli_fetch_array($get_time_query)){
                    date_default_timezone_set('Africa/Lagos');
                    $time = $get_time['last_seen'];
                    if(strtotime($time) >= strtotime("-1 minutes")){
                        $i++;
                    }
                }
            }
            echo $i;
        }
    }

    public function activeFriends($user){
        $sql = mysqli_query($this->conn, "SELECT * FROM friends WHERE user_one='$user' OR user_two='$user'");
        if(mysqli_num_rows($sql) == 0){
            return '<li><div class="sFLi">You have no friend</li></div>';
        } else {
            while($friends = mysqli_fetch_assoc($sql)){
                $user_one = $friends['user_one'];
                $user_two = $friends['user_two'];
                $myFriend = "";
                if($user_one == $user){
                    $myFriend .= $user_two;
                }
                if($user_two == $user){
                    $myFriend .= $user_one;
                }
                $get_time_query = mysqli_query($this->conn, "SELECT * FROM users WHERE username='$myFriend'");
                while($get_time = mysqli_fetch_array($get_time_query)){
                    date_default_timezone_set('Africa/Lagos');
                    $time = $get_time['last_seen'];
                    if(strtotime($time) >= strtotime("-1 minutes")){
                        $username = $get_time['username'];
                        $konU = new User($this->conn, $this->user_obj);
                        $name = $konU->getFullName($username);
                        $img = $konU->getDp($username);
                        ?>
                            <li>
                                <div class="sFLi">
                                    <div class="sFLi-inner">
                                        <a href="/u/<?php echo $username; ?>">                                            
                                            <img src="<?php echo $img; ?>" class="img">
                                        </a>
                                    </div>
                                    <div class="sFLiW">
                                        <a href="/u/<?php echo $username; ?>/">
                                            <div class="sULi"><?php echo $name; ?></div>
                                        </a>
                                    </div>
                                    <div class="sFLiI">
                                        <div class="onlineImg"></div>
                                    </div>
                                </div>
                            </li>
                        <?php
                    }
                }
            }
        }
    }

    public function getAllReq($user_to){
        $notQuery = mysqli_query($this->conn, "SELECT * FROM requests WHERE user_to='$user_to'");
        return mysqli_num_rows($notQuery);
    }

    public function friendRequests($user_to){
        $sqlsss = mysqli_query($this->conn, "SELECT * FROM requests WHERE user_to='$user_to'");
        if(mysqli_num_rows($sqlsss) == 0){
            return "<span class='fRLi'>You have no friend requests</span>";
        } else {
            $i = 0;
            while($noti = mysqli_fetch_assoc($sqlsss)){
                $i++;
                $id = $noti['id'];
                $from = $noti['user_from'];
                $to = $noti['user_to'];
                $date = $noti['date_time'];
                $lvOnline = $user_to;
                $lvUser = new User($this->conn, $from);
                $lvNotify = new Notifications($this->conn, $from);
                $dp = $lvUser->getDp($from);
                $time = $lvNotify->timeago($date);
                ?>
                    <li style="display: inline-flex;">
                        <img src="<?php echo $dp; ?>" class="img" style="margin: auto;">
                        <span style="width: 200px;overflow:hidden;min-height:35px;line-height:24px;">
                            <?php echo $lvUser->getFullName($from); ?> sent you a friend request
                        </span>
                        <span style="width: 50px;margin: auto;" id="req<?php echo $i; ?>">
                            <div style="display: inline-flex;">
                                <form method="POST" id="accept<?php echo $i; ?>">
                                    <input type="text" name="user_from" value="<?php echo $from; ?>" hidden>
                                    <input type="text" name="user_to" value="<?php echo $lvOnline; ?>" hidden>
                                    <input type="text" name="accept" hidden>
                                    <button type="submit" title="Accept request" class="btn btn-success"><i class="fa fa-user-plus"></i></button>
                                </form>
                                <form method="POST" id="decline<?php echo $i; ?>" style="margin-left: 2px;">
                                    <input type="text" name="user_from" value="<?php echo $from; ?>" hidden>
                                    <input type="text" name="user_to" value="<?php echo $lvOnline; ?>" hidden>
                                    <input type="text" name="decline" hidden>
                                    <button type="submit" title="Decline Request" class="btn btn-danger" id="decBtn" style="background-color: red;"><i class="fa fa-user-alt-slash"></i></button>
                                </form>
                            </div>
                        </span>
                    </li>
                    <script>
                        $(document).ready(function() {
                            $('#accept<?php echo $i; ?>').on("submit", function(e){
                                e.preventDefault();
                                $.ajax({
                                    url: "/friendsL.php",
                                    type: 'POST',
                                    data: $("#accept<?php echo $i; ?>").serialize(),
                                    beforeSend:function(){
                                        $("#accept<?php echo $i; ?>").html('<i class="fa fa-spinner fa-spin"></i>');
                                    },
                                    success:function(response){
                                        $("#req<?php echo $i; ?>").load('/ff.php?profileO=<?php echo $from; ?>');
                                    }
                                });
                            });
                            $('#decline<?php echo $i; ?>').on("submit", function(e){
                                e.preventDefault();
                                $.ajax({
                                    url: "/friendsL.php",
                                    type: 'POST',
                                    data: $("#decline<?php echo $i; ?>").serialize(),
                                    beforeSend:function(){
                                        $("#decline<?php echo $i; ?>").html('<i class="fa fa-spinner fa-spin"></i>');
                                    },
                                    success:function(response){
                                        $("#req<?php echo $i; ?>").load('/ff.php?profileO=<?php echo $from; ?>');
                                    }
                                });
                            });
                        });
                    </script>
                <?php
            }
        }
    }
}
