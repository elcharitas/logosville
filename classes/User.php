<?php
class User
{
    public $conn;
    public $user;

    public function __construct($conn, $user)
    {
        $this->conn = $conn;
        $this->user = $user;
    }

    public function getFirstName($user){
        $sql = mysqli_query($this->conn, "SELECT * FROM users WHERE username='$user'");
        $theUser = mysqli_fetch_array($sql);
        return ucfirst($theUser['first']);
    }

    public function getLastName($user){
        $sql = mysqli_query($this->conn, "SELECT * FROM users WHERE username='$user'");
        $theUser = mysqli_fetch_array($sql);
        return ucfirst($theUser['last']);
    }

    public function getOtherName($user){
        $sql = mysqli_query($this->conn, "SELECT * FROM users WHERE username='$user'");
        $theUser = mysqli_fetch_array($sql);
        return ucfirst($theUser['other']);
    }

    public function getEmail($user){
        $sql = mysqli_query($this->conn, "SELECT * FROM users WHERE username='$user'");
        $theUser = mysqli_fetch_array($sql);
        return $theUser['email'];
    }

    public function getPhone($user){
        $sql = mysqli_query($this->conn, "SELECT * FROM users WHERE username='$user'");
        $theUser = mysqli_fetch_array($sql);
        return ucfirst($theUser['phone']);
    }

    public function getDOB($user){
        $sql = mysqli_query($this->conn, "SELECT * FROM users WHERE username='$user'");
        $theUser = mysqli_fetch_array($sql);
        return ucfirst($theUser['dob']);
    }

    public function getCountry($user){
        $sql = mysqli_query($this->conn, "SELECT * FROM users WHERE username='$user'");
        $theUser = mysqli_fetch_array($sql);
        return ucfirst($theUser['country']);
    }

    public function getGender($user){
        $sql = mysqli_query($this->conn, "SELECT * FROM users WHERE username='$user'");
        $theUser = mysqli_fetch_array($sql);
        return ucfirst($theUser['gender']);
    }

    public function getFullName($user){
        $sql = mysqli_query($this->conn, "SELECT * FROM users WHERE username='$user'");
        $theUser = mysqli_fetch_array($sql);
        return ucfirst($theUser['last']) . " " . ucfirst($theUser['first']) . " " . ucfirst($theUser['other']);
    }

    public function getName($user){
        $sql = mysqli_query($this->conn, "SELECT * FROM users WHERE username='$user'");
        $theUser = mysqli_fetch_array($sql);
        return ucfirst($theUser['last']) . " " . ucfirst($theUser['first']);
    }

    public function getDp($user){
        $sql = mysqli_query($this->conn, "SELECT * FROM users WHERE username='$user'");
        $theUser = mysqli_fetch_array($sql);
        return ucfirst($theUser['dp']);
    }

    public function getCover($user){
        $sql = mysqli_query($this->conn, "SELECT * FROM users WHERE username='$user'");
        $theUser = mysqli_fetch_array($sql);
        return ucfirst($theUser['cover']);
    }

    public function activeUsers(){
        $sql = mysqli_query($this->conn, "SELECT * FROM users WHERE verified='1' ");
        if(mysqli_num_rows($sql) > 0){
            while($friends = mysqli_fetch_assoc($sql)){
                $time = $friends['last_seen'];
                if(strtotime($time) > strtotime("-1 minutes")){
                    $userOnline = $friends['username'];
                    if($userOnline != $this->user){
                        $konU = new User($this->conn, $this->user);
                        $name = $konU->getFullName($userOnline);
                        ?>
                            <div class="row">
                                <a href="/u/<?php echo $userOnline; ?>" class="listFriends">
                                    <div class="col-lg-12 listFriends">
                                        <?php echo $name; ?>
                                    </div>
                                </a>
                            </div>
                        <?php
                    }
                }
            }
        } else {
            return "No user";
        }
    }

    public function allUsers(){
        $sql = mysqli_query($this->conn, "SELECT * FROM users WHERE verified='1' ");
        if(mysqli_num_rows($sql) > 0){
            while($friends = mysqli_fetch_assoc($sql)){
                $userOnline = $friends['username'];
                if($userOnline != $this->user){
                    return $userOnline;
                }
            }
        } else {
            return "No user";
        }
    }

    public function getMyPages($user){
        $pagesQuery = mysqli_query($this->conn, "SELECT * FROM pages WHERE page_creator='$user' AND deleted='no' ORDER BY id DESC LIMIT 4");
        if(mysqli_num_rows($pagesQuery) > 0){
            while($page = mysqli_fetch_assoc($pagesQuery)){
                $pageName = $page['page_name'];
                $pageId = $page['page_uniqID'];
                $pageDp = $page['page_pic'];
                $pageNotices = $page['page_notification'];
                ?>
                    <li>
                        <div class="_55lp">
                            <div class="_4bl7">
                                <a href="/<?php echo $pageId; ?>/page">
                                    <div class="_55lq">
                                        <div class="_55lt" style="width: 32px; height: 32px;">
                                            <img src="<?php echo $pageDp; ?>" alt="" class="img" width="32" height="32">
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="_4bl9">
                                <a class="_224p" href="/<?php echo $pageId; ?>/page">
                                    <div class="_55lr"><?php echo $pageName; ?></div>
                                </a>
                            </div>
                            <div class="_3p8_ ">
                                <div class="_5bon">
                                    <span class="_4fsv"><?php echo $pageNotices; ?></span>
                                </div>
                            </div>
                        </div>
                    </li>
                <?php
            }
        } else {
            ?>
                <li>You have no Page</li>
            <?php
        }
    }

    public function groupConvos($user){
        $sql = mysqli_query($this->conn, "SELECT * FROM groupconvomem WHERE username='$user' LIMIT 5");
        if(mysqli_num_rows($sql) == 0){
            ?>
                <a class="_55ln _qhr" rel="ignore" tabindex="0"><div class="_55lp"><div class="_55lq _tt_" aria-hidden="true"><div class="crGroup"></div></div><div class="_55lr">Create New Group</div></div></a>
            <?php
        } else {
            while($group = mysqli_fetch_assoc($sql)){
                $uniqid = $group['groupConvoUniqID'];
                $get_time_query = mysqli_query($this->conn, "SELECT * FROM groupConvos WHERE username='$uniqid'");
                while($get_time = mysqli_fetch_array($get_time_query)){
                    $time = $get_time['last_seen'];
                    if(strtotime($time) > strtotime("-1 minutes")){
                        $username = $get_time['username'];
                        $konU = new User($this->conn, $this->user_obj);
                        $name = $konU->getFullName($username);
                        $img = $konU->getDp($username);
                        ?>
                            <li>
                                <div class="_55lp">
                                    <div class="_4bl7">
                                        <a href="/gc/<?php echo $username; ?>/notifications/">
                                            <div class="_55lq">
                                                <div class="_55lt" style="width: 32px; height: 32px;">
                                                    <img src="<?php echo $img; ?>" alt="" class="img" width="32" height="32">
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="_4bl9">
                                        <a class="_224p" href="/gc/<?php echo $username; ?>/notifications/">
                                            <div class="_55lr"><?php echo $name; ?></div>
                                        </a>
                                    </div>
                                    <div class="_3p8_ ">
                                        <div class="_5bon">
                                            <span class="_4fsv">
                                                <i class="fa fa-dot-circle text-success"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        <?php
                    } else {
                        $msg_check = mysqli_query($this->conn, "SELECT * FROM inner_chat WHERE deleted='no'");
                        return "There are no friends online";
                    }
                }
            }
        }
    }
}
