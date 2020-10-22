<?php
class Messages
{
    public $conn;
    public $user_obj;

    public function __construct($conn, $user_obj){
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

    public function timeago2($date) {
        date_default_timezone_set('Africa/Lagos');
        $timestamp = strtotime($date);	
        
        $strTime = array("secs", "mins", "hours", "days", "mons", "years");
        $length = array("60","60","24","30","12","10");
 
        $currentTime = time();
        if($currentTime >= $timestamp) {
            $diff     = time()- $timestamp;
            for($i = 0; $diff >= $length[$i] && $i < count($length)-1; $i++) {
                $diff = $diff / $length[$i];
            }
 
            $diff = round($diff);
            return $diff . " " . $strTime[$i] . " ago";
        }
    }

    public function myMessages($user){
        $sql = mysqli_query($this->conn, "SELECT * FROM inner_chat WHERE user_to='$user' AND seen_status='no' ORDER BY id DESC ");
        if(mysqli_num_rows($sql) == 0){
            return "";
        } else {
            return "(".mysqli_num_rows($sql).")";
        }
    }

    public function recentMessages($owner, $other){
        if($other == ""){
            ?>
                <li>
                    You have no recent conversation
                </li>
            <?php
        } else{
            foreach ($other as $oth) {
                # code...
                $msgQuery = mysqli_query($this->conn, "SELECT * FROM inner_chat WHERE (user_to='$owner' AND user_from='$oth') OR (user_from='$owner' AND user_to='$oth') ORDER BY id DESC LIMIT 1");
                if(mysqli_num_rows($msgQuery) != 0){
                    while($getMsg = mysqli_fetch_assoc($msgQuery)){
                        $user_from = $getMsg['user_from'];
                        $user_to = $getMsg['user_to'];
                        $bdy = $getMsg['message'];
                        $del = $getMsg['deleted'];
                        if($user_from == $owner){
                            $from = 'You';
                        } else {
                            $from = $user_from;
                        }
                        $lvUser = new User($this->conn, $oth);
                        $name = $lvUser->getFullName($oth);
                        

                        if($user_from == $owner){
                            $lastMsg = "You: " . $bdy;
                        } else {
                            $lname = $lvUser->getLastName($user_from);
                            $lastMsg = $lname . ": " . $bdy;
                        }
                        if($del == 'no'){
                            ?>
                                <li>
                                    <span>
                                        <a title="Conversation with <?php echo $name; ?>" style='display:inline-flex;' href="/m/<?php echo $oth; ?>">
                                            <img src="<?php echo $lvUser->getDp($oth); ?>" width="50" height="50">
                                            <div> 
                                                <?php echo $name; ?>
                                                <span title="Conversation with <?php echo $name; ?>" style='display:inline-flex;'><?php echo $lastMsg; ?></span>
                                            </div>
                                        </a>
                                    </span>
                                    
                                </li>
                                <style>
                                    li{
                                        list-style: none;
                                    }
                                </style>
                            <?php
                            }
                    }
                }
            }
        }
    }

    public function getConvos($user_one, $user_two){
        $lvOnline = $user_two;
        $lvUser = new User($this->conn, $lvOnline);
        $msgQuery = mysqli_query($this->conn, "SELECT * FROM inner_chat WHERE (user_to='$user_one' AND user_from='$user_two') OR (user_from='$user_one' AND user_to='$user_two') ORDER BY id ASC");
        if(mysqli_num_rows($msgQuery) == 0){
            ?>
                <div>
                    <p style="text-align:center;margin:auto;margin-top:10px"><img src="<?php echo $lvUser->getDp($lvOnline); ?>" style="width: 25px;border-radius:100%;background:white;height:25px;" class="img-circle" ></p>
                    <p style="text-align:center;font-size:12px;">Start a conversation</p>
                </div>
            <?php
        } else {
            $i = 0;
            $j = 0;
            ?>
                <div title="<?php echo $lvUser->getFullName($user_one) ?>">
                    <p style="text-align:center;margin:auto;margin-top:10px">
                        <a href="/u/<?php echo $user_one; ?>">
                            <img src="<?php echo $lvUser->getDp($lvOnline); ?>" style="width: 25px;border-radius:100%;background:white;height:25px;" class="img-circle" >
                        </a>
                    </p>
                    <p style="text-align:center;font-size:12px;">
                        <a href="/u/<?php echo $user_one; ?>">
                            <?php echo $lvUser->getFullName($user_one) ?>
                        </a>
                    </p>
                </div><hr>
            <?php
            while($get = mysqli_fetch_assoc($msgQuery)){
                $msg = $get['message'];
                $mID = $get['id'];
                $from = $get['user_from'];
                $to = $get['user_to'];
                $seenStat = $get['seen_status'];
                $rectat = $get['received_status']; 
                $time = $get['created_at'];
                $lvMsg = new Messages($this->conn, $lvOnline);
                $timeN = $lvMsg->timeago2($time);
                $del = $get['deleted'];
                if($del == 'no'){
                    if($from == $lvOnline){
                        $i++;
                        
                        if($seenStat == 'yes' && $rectat == 'yes'){
                            $ss = '<i class="fas fa-check '. (($to != $lvOnline) ? 'fromS' : 'toS') .'"></i><i class="fas fa-check '. (($to != $lvOnline) ? 'fromS' : 'toS') .'"></i>';
                        } else if($seenStat == 'yes'){
                            $ss = '<i class="fas fa-check '. (($to != $lvOnline) ? 'fromS' : 'toS') .'"></i>';
                        } else {
                            $ss = "";
                        }
                    } else {
                        $ss = "";
                        $j++;
                    }
                    ?>
                        <div class="text">
                            <div class="<?php echo (($to != $lvOnline) ? 'text-right' : 'text-left'); ?>">
                                <div class="<?php echo (($to != $lvOnline) ? 'from' : 'to'); ?>" id="<?php echo (($to != $lvOnline) ? 'from' . $j : 'to' . $i); ?>" style="text-align: justify;">
                                    <?php echo $msg; ?>
                                    <div class="stats <?php echo (($to != $lvOnline) ? 'text-right' : 'text-left'); ?>">
                                        <?php echo $timeN . " " . $ss; ?>  
                                    </div>
                                </div>
                                <div id="<?php echo (($to != $lvOnline) ? 'other' . $j : 'other' . $i); ?>">
                                    <?php
                                        if(($seenStat == 'yes' && $rectat == 'no' && $to != $lvOnline) || $seenStat == 'no' && $to != $lvOnline){
                                            ?>
                                                <form id="delMF<?php echo $mID; ?>">
                                                    <input type="hidden" name="delID" value="<?php echo $mID; ?>">
                                                    <button type="submit" id="delMsg<?php echo $mID; ?>" name="delMsg" style="font-size: 8px;border:none;background:transparent"><i class="fa fa-times text-danger"></i></button>
                                                </form>
                                            <?php
                                        }
                                    ?>
                                </div>
                            </div>
                        </div>
                        <script>
                            $(document).ready(function(){
                                $("#delMF<?php echo $mID; ?>").on("submit", function(e){
                                    e.preventDefault();
                                    $.ajax({
                                        data: $("#delMF<?php echo $mID; ?>").serialize(),
                                        url:'/msg',
                                        type: 'POST',
                                        beforeSend: function(){
                                            $("#delMsg<?php echo $mID; ?>").html('<i class="fa fa-spinner fa-spin fa-2x"></i>');
                                        },
                                        success: function(response){
                                            $("#delMsg<?php echo $mID; ?>").html('<i class="fa fa-times text-danger" style="transform: rotate(50deg);"></i>');
                                            $('#allMM').load("/msg.php?convos=<?php echo $to; ?>");
                                        }
                                    });
                                });
                                $("#to<?php echo $i; ?>").on("click", function(e){
                                    e.preventDefault();
                                    $.ajax({
                                        data: $("#newMsg").serialize(),
                                        url:'/msg',
                                        type: 'POST',
                                        beforeSend: function(){
                                            $("#sendMsg").html('<i class="fa fa-spinner fa-spin fa-2x"></i>');
                                        },
                                        success: function(response){
                                            $("#sendMsg").html('<i class="fa fa-2x fa-paper-plane" style="transform: rotate(50deg);"></i>');
                                        }
                                    });
                                });
                            });
                        </script>
                    <?php
                    (($to != $lvOnline) ? 'right' : 'left');
                }
            }
        }
    }

    public function recentMsg($owner, $other){
        if($other == ""){
            ?>
                <li>
                    You have no recent conversation
                </li>
            <?php
        } else{
            foreach ($other as $oth) {
                # code...
                $msgQuery = mysqli_query($this->conn, "SELECT * FROM inner_chat WHERE (user_to='$owner' AND user_from='$oth') OR (user_from='$owner' AND user_to='$oth') ORDER BY created_at DESC LIMIT 1");
                if(mysqli_num_rows($msgQuery) != 0){
                    while($getMsg = mysqli_fetch_assoc($msgQuery)){
                        $user_from = $getMsg['user_from'];
                        $user_to = $getMsg['user_to'];
                        $bdy = $getMsg['message'];
                        $del = $getMsg['deleted'];
                        if($user_from == $owner){
                            $from = 'You';
                        } else {
                            $from = $user_from;
                        }
                        $lvUser = new User($this->conn, $oth);
                        $name = $lvUser->getFullName($oth);
                        

                        if($user_from == $owner){
                            $lastMsg = "You: " . $bdy;
                        } else {
                            $lname = $lvUser->getLastName($user_from);
                            $lastMsg = $lname . ": " . $bdy;
                        }
                        if($del == 'no'){
                            ?>
                                <li>
                                    <span><a title="Conversation with <?php echo $name; ?>" style='display:inline-flex;' href="/m/<?php echo $oth; ?>">
                                        <img src="<?php echo  $lvUser->getDP($oth); ?>" width="40px" height="40"><?php echo  $name; ?></a>
                                    </span><br>
                                    <span title="Conversation with <?php echo $name; ?>" style='display:inline-flex;max-width: 100%;overflow:hidden;padding-left: 40px;'><?php echo $lastMsg; ?></span>
                                </li>
                                <style>
                                    li{
                                        list-style: none;
                                    }
                                </style>
                            <?php
                        }
                    }
                }
            }
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
                    <option class="toOption" value="<?php echo $username; ?>"><?php echo $name; ?></option>
                    <style>
                        .toOptioon{
                            height: 40px;
                            text-align: left;
                        }
                    </style>
                <?php
            }
        }
    }

    public function friends($user){
        $sql = mysqli_query($this->conn, "SELECT * FROM friends WHERE user_one='$user' OR user_two='$user'");
        if(mysqli_num_rows($sql) == 0){
            return '';
        } else {
            $i = 0;
            $return = array();
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
                $i++;
                $sqls = mysqli_query($this->conn, "SELECT * FROM users WHERE verified='1' AND username='$myFriend' ");
                $theUser = mysqli_fetch_array($sqls);
                $username = $theUser['username'];
                array_push($return, $username);
            }
            return $return;
        }
    }
}
