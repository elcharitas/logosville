<?php
class Posts
{
    public $conn;
    public $user_obj;

    public function __construct($conn, $user_obj)
    {
        $this->conn = $conn;
        $this->user = $user_obj;
    }

    public function getComments($uniqID){
        $sql = mysqli_query($this->conn, "SELECT * FROM comments WHERE postID='$uniqID'");
        return mysqli_num_rows($sql);
    }

    public function getLikes($uniqID){
        $sql = mysqli_query($this->conn, "SELECT * FROM likes WHERE uniqID='$uniqID'");
        return mysqli_num_rows($sql);
    }

    public function getNumRep($commid){
        $sql = mysqli_query($this->conn, "SELECT * FROM commrep WHERE commID='$commid'");
        return mysqli_num_rows($sql);
    }

    public function getLikeStat($uniqID, $user){
        $sql = mysqli_query($this->conn, "SELECT * FROM likes WHERE uniqID='$uniqID' AND username='$user'");
        if(mysqli_num_rows($sql) == 0){
            return false;
        } else {
            return true;
        }
    }

    public function getImages($imageID){
        $sql = mysqli_query($this->conn, "SELECT location FROM images WHERE imageID='$imageID' AND deleted='no' ORDER BY id DESC");
        if(mysqli_num_rows($sql) == 0){
            return false;
        } else {
            $i = 0;
            $imgF = '<div class="createImg">';
            if(mysqli_num_rows($sql) == 1){
                $pNme = mysqli_fetch_assoc($sql);
                $img = $pNme['location'];
                $imgF .= '<img src="'.$img.'" style="max-width:100%;max-height:100%;width:auto;height:300px;margin:auto" alt="">';
            } else if(mysqli_num_rows($sql) >= 2) {
                while($pNme = mysqli_fetch_assoc($sql))
                {    
                    $img = $pNme['location'];
                    $imgF .= '<img src="'.$img.'" style="max-width:50%;max-height:50%;width:auto;height:300px;">';
                }
            }
            $imgF .= '</div>';
            return $imgF;
        }        
    }

    public function getImagesSingle($imageID){
        $sql = mysqli_query($this->conn, "SELECT location FROM images WHERE imageID='$imageID' AND deleted='no' ORDER BY id DESC");
        if(mysqli_num_rows($sql) == 0){
            return false;
        } else {
            $i = 0;
            $imgF = '<div style="width:300px;margin:auto">';
            if(mysqli_num_rows($sql) == 1){
                $pNme = mysqli_fetch_assoc($sql);
                $img = $pNme['location'];
                $imgF .= '<img src="'.$img.'" style="width:300px;height:400px;margin:auto" alt="">';
            } else if(mysqli_num_rows($sql) >= 2) {
                while($pNme = mysqli_fetch_assoc($sql))
                {    
                    $img = $pNme['location'];
                    $imgF .= '<img src="'.$img.'" style="width:300px;height:400px;margin:auto">';
                }
            }
            $imgF .= '</div>';
            return $imgF;
        }        
    }

    public function getPostedBy($posted_by){
        $lvUser = new User($this->conn, $posted_by);
        return '<span>
                    <a href="/u/'. $posted_by .'">
                        <img src="' . $lvUser->getDp($posted_by) . '" class="img" > &nbsp;' . $lvUser->getFullName($posted_by) . '
                    </a>
                </span>';
    }

    public function getPostedTo($posted_to){
        if($posted_to == ""){
            return "";
        } else {
            $lvUser = new User($this->conn, $posted_to);
            return '<span> to
                        <a href="/u/'. $posted_to .'">
                            ' . $lvUser->getFullName($posted_to) . '
                        </a>
                    </span>';
        }
    }

    public function getAllComments($uniqID){
        $user = $_SESSION['lvOnline'];
        $poQue = mysqli_query($this->conn, "SELECT * FROM posts WHERE uniqID='$uniqID'");
        $getPo = mysqli_fetch_array($poQue);
        $j = $getPo['id'];
        $sql = mysqli_query($this->conn, "SELECT * FROM comments WHERE postID='$uniqID' ORDER BY id DESC LIMIT 4");
        if(mysqli_num_rows($sql) == 0){
            return "";
        } else {
            $i = 0;
            while($get = mysqli_fetch_assoc($sql)){
                $i = $get['id'];
                $by = $get['posted_by'];
                $body = nl2br($get['body']);
                $sqls = mysqli_query($this->conn, "SELECT * FROM users WHERE username='$by'");
                $theUser = mysqli_fetch_array($sqls);
                $fname = ucfirst($theUser['last']) . " " . ucfirst($theUser['first']) . " " . ucfirst($theUser['other']);
                $dp = $theUser['dp'];
                $lvPost = new Posts($this->conn, $user);

                if($by == $user){
                    $delB = '
                    <form id="delRepForm'.$i.'" style="width:40px;">
                        <div class="delText">
                            <input type="hidden" value="'.$user.'" name="by">
                            <input type="hidden" value="'.$by.'" name="to">
                            <input type="hidden" value="'.$uniqID.'" name="idD">
                            <button style="background:transparent;width:30px;border:none;" id="delB'.$i.'">delete</button>
                        </div>
                    </form>';
                } else {
                    $delB = "";
                }
                ?>
                    <div style="margin:10px auto">
                        <div class="row">
                            <div class="col-1">
                                <a href="/u/'. $by .'"><img src="<?php echo $dp; ?>" style="height:35px;width:35px;margin:auto;" ></a>
                            </div>
                            <div class="col-11 row">
                                <span class="col-12"><a  href="/u/<?php echo $by; ?>"><?php echo $fname; ?></a></span>
                                <span class="col-12"><?php echo $body; ?></span>
                                <div class="col-12" style="font-size: 10px;display:inline-flex;line-height:20px">
                                    <a id="repCom<?php echo $i; ?>" >reply(<?php echo $lvPost->getNumRep($i); ?>)</a>&nbsp;&nbsp;&nbsp;<a href="#" >Like</a>&nbsp;&nbsp;&nbsp;<?php echo $delB; ?>
                                </div>
                                <div class="col-12">
                                    <form id="repComForm<?php echo $i; ?>" style="display:none;">
                                        <div class="form-group" style="display: inline-flex;">
                                            <textarea name="repComText<?php echo $i; ?>" id="repComText<?php echo $i; ?>" class="form-control" cols="20" rows="1" placeholder="Reply..."></textarea>
                                            <button type="submit" id="repBtn<?php echo $i; ?>" class="btn btn-success">Reply</button>
                                        </div>
                                        
                                    </form>    
                                </div>
                            </div>
                        </div>
                    </div>
                    <script>
                        $(document).ready(function() {
                            $("#repCom<?php echo $i; ?>").on("click", function(e){
                                e.preventDefault();
                                $("#repComForm<?php echo $i; ?>").css('display', 'block');
                            });
                            $("#repBtn<?php echo $i; ?>").on("click", function(e){
                                e.preventDefault();
                                var commid = <?php echo $i; ?>;
                                var textId = $("#repComText<?php echo $i; ?>").val();
                                $.ajax({
                                    url: 'comment.php',
                                    data: 'reply&commId=' + commid + "&textId=" + textId,
                                    type: 'POST',
                                    success: function(){
                                        $('#comments<?php echo $i; ?>').load('/numCom.php?id=<?php echo $uniqID; ?>');
                                        $('#repComForm<?php echo $i; ?>').trigger("reset");
                                        $('#allComm<?php echo $j; ?>').load('/allComments.php?id=<?php echo $uniqID; ?>');
                                    }
                                });
                            });
                        });
                    </script>
                <?php
                echo '
                    <style>
                        #repCom'.$i.':hover{
                            cursor: pointer;
                        }
                    </style>
                ';
            } 
        }
    }

    public function recentPosts($user) {
        $lvUser = new User($this->conn, $user);
        $name = $lvUser->getFirstName($user);
        $sql = mysqli_query($this->conn, "SELECT * FROM posts ORDER BY id DESC");
        $return = "";
        if(mysqli_num_rows($sql) == 0){
            return false;
        } else {
            $return = '<div class="createP2">';
            while($pNme = mysqli_fetch_assoc($sql)){
                $i = $pNme['id'];
                $content = $pNme['body'];
                $uniqID = $pNme['uniqID'];
                $imageID = $pNme['images'];
                $posted_by = $pNme['posted_by'];
                $posted_to = $pNme['posted_to'];
                $body = substr($pNme['body'],0,300). '<br> <a href="/posts/'.$uniqID.'" style="float:right;">see full post...</a>';
                $likes = $pNme['likes'];
                $comments = $pNme['comments'];
                $return = '<div class="createP2">';
                if($imageID == ""){
                    $image = "";
                } else {
                    $lvPostImg = new Posts($this->conn, $posted_by);
                    $return .= '<div class="createH2">' . $lvPostImg->getPostedBy($posted_by) .  $lvPostImg->getPostedTo($posted_to) . '</div>';
                    if($posted_by == $user){
                        $return .= '
                        <form id="delForm'.$i.'" style="width:40px;float:right;">
                            <div class="delText">
                                <input type="hidden" value="'.$user.'" name="by">
                                <input type="hidden" value="'.$posted_by.'" name="to">
                                <input type="hidden" value="'.$uniqID.'" name="idD">
                                <button style="background:transparent;width:30px;height:30px;border:none;" id="delB'.$i.'"><i title="Delete" class="fa fa-times" style="color:red;"></i></button>
                            </div>
                        </form>';
                    }
                    $return .= '<div class="createText">' . $body . "</div>";
                    $return .= $lvPostImg->getImages($imageID);
                    $return .= '<div class="createFooter">
                                    <div class="createFooterTop">
                                        <form id="cmtForm'.$i.'">
                                            <div class="form-group commentText">
                                                <textarea class=".cTA form-control" name="cmtText" id="tA'.$i.'" required rows="1" placeholder="'. $name .' post a comment"></textarea>
                                                <input type="hidden" value="'.$user.'" name="by">
                                                <input type="hidden" value="'.$posted_by.'" name="to">
                                                <input type="hidden" value="'.$uniqID.'" name="uniqId">
                                                <button class="btn btn-primary" id="commB'.$i.'">Send</button>
                                            </div>
                                        </form>
                                        <form id="likForm'.$i.'" style="display: inline-flex;line-height:35px;">
                                            <div class="likTexct">
                                                <input type="hidden" value="'.$user.'" name="by">
                                                <input type="hidden" value="'.$posted_by.'" name="to">
                                                <input type="hidden" value="'.$uniqID.'" name="idL">
                                                <button style="background:transparent;width:30px;height:30px;border:none;" id="likB'.$i.'"><span id="likStat'.$i.'"></span></button>
                                            </div>
                                            &nbsp&nbspLikes :  <span id="likes'.$i.'"></span>&nbsp&nbsp&nbsp&nbsp Comments :  <span id="comments'.$i.'"></span>
                                        </form>';
                                    $return .= '                                        
                                    </div>
                                    <div class="createFooterBottom" id="allComm'.$i.'"></div>
                                </div>';
                    ?>
                        <script>
                            $(document).ready(function(){
                                $.ajax({
                                    type: 'post',
                                    url: '/allComments.php',
                                    data: 'id=<?php echo $uniqID; ?>',
                                    success: function (data) {
                                        $('#allComm<?php echo $i; ?>').html(data);
                                    }
                                });
                                $.ajax({
                                    type: 'get',
                                    url: '/numCom.php?id=<?php echo $uniqID; ?>',
                                    success: function (response) {
                                        $('#comments<?php echo $i; ?>').html(response);
                                    }
                                });
                                $.ajax({
                                    type: 'get',
                                    url: '/likStat.php?id=<?php echo $uniqID; ?>',
                                    success: function (response) {
                                        $('#likStat<?php echo $i; ?>').html(response);
                                    }
                                });
                                $.ajax({
                                    type: 'get',
                                    url: '/numLikes.php?id=<?php echo $uniqID; ?>',
                                    success: function (response) {
                                        $('#likes<?php echo $i; ?>').html(response);
                                    }
                                });
                                $('#cmtForm<?php echo $i; ?>').on('submit', function(e) {
                                    e.preventDefault();
                                    $('#commB<?php echo $i; ?>').html("<i class='fa fa-spinner fa-spin'></i>");
                                    $.ajax({
                                        type: 'post',
                                        url: '/comment.php',
                                        data: $('#cmtForm<?php echo $i; ?>').serialize(),
                                        beforeSend: function(){
                                            $('#comments<?php echo $i; ?>').html("<i class='fa fa-spinner fa-spin'></i>");
                                        },
                                        success: function (response) {
                                            $('#commB<?php echo $i; ?>').delay("slow").html("Send");
                                            $('#comments<?php echo $i; ?>').load('/numCom.php?id=<?php echo $uniqID; ?>');
                                            $('#cmtForm<?php echo $i; ?>').trigger("reset");
                                            $('#allComm<?php echo $i; ?>').load('/allComments.php?id=<?php echo $uniqID; ?>');
                                        }
                                    });
                                });
                                $('#likForm<?php echo $i; ?>').on('submit', function(e) {
                                    e.preventDefault();
                                    $.ajax({
                                        type: 'post',
                                        url: '/numLikes.php',
                                        data: $('#likForm<?php echo $i; ?>').serialize(),
                                        beforeSend: function(){
                                            $('#likStat<?php echo $i; ?>').html("<i class='fa fa-spinner fa-spin'></i>");
                                        },
                                        success: function (response) {
                                            $('#liks<?php echo $i; ?>').html(response);
                                            $("#likStat<?php echo $i; ?>").load('/likStat.php?id=<?php echo $uniqID; ?>'); 
                                            $('#likes<?php echo $i; ?>').load('/numLikes.php?id=<?php echo $uniqID; ?>');
                                        }
                                    });
                                });
                                $('#delForm<?php echo $i; ?>').on('submit', function(e) {
                                    e.preventDefault();
                                    $.ajax({
                                        type: 'post',
                                        url: '/numLikes.php',
                                        data: $('#delForm<?php echo $i; ?>').serialize(),
                                        success: function (response) {
                                            window.location.reload();
                                        }
                                    });
                                });
                            });
                        </script>
                    <?php
                }
                $return .= '</div>';
                echo $return;
            }   
        }
    }

    public function recentProPosts($user) {
        $lvUser = new User($this->conn, $user);
        $name = $lvUser->getFirstName($user);
        $sql = mysqli_query($this->conn, "SELECT * FROM posts WHERE posted_to='$user' OR posted_by='$user' ORDER BY id DESC");
        $return = "";
        if(mysqli_num_rows($sql) == 0){
            return false;
        } else {
            $return = '<div class="createP2">';
            while($pNme = mysqli_fetch_assoc($sql)){
                $i = $pNme['id'];
                $content = $pNme['body'];
                $uniqID = $pNme['uniqID'];
                $imageID = $pNme['images'];
                $posted_by = $pNme['posted_by'];
                $posted_to = $pNme['posted_to'];
                $body = substr($pNme['body'],0,300). '<br> <a href="/posts/'.$uniqID.'" style="float:right;">see full post...</a>';
                $likes = $pNme['likes'];
                $comments = $pNme['comments'];
                $return = '<div class="createP2">';
                if($imageID == ""){
                    $image = "";
                } else {
                    $lvPostImg = new Posts($this->conn, $posted_by);
                    $return .= '<div class="createH2">' . $lvPostImg->getPostedBy($posted_by) .  $lvPostImg->getPostedTo($posted_to) . '</div>';
                    $return .= '<div class="createText">' . $body . "</div>";
                    $return .= $lvPostImg->getImages($imageID);
                    if($posted_by == $user){
                        $return .= '
                        <form id="delForm'.$i.'" style="width:40px;float:right;">
                            <div class="delText">
                                <input type="hidden" value="'.$user.'" name="by">
                                <input type="hidden" value="'.$posted_by.'" name="to">
                                <input type="hidden" value="'.$uniqID.'" name="idD">
                                <button style="background:transparent;width:30px;height:30px;border:none;" id="delB'.$i.'"><i title="Delete" class="fa fa-times" style="color:red;"></i></button>
                            </div>
                        </form>';
                    }
                    $return .= '<div class="createFooter">
                                    <div class="createFooterTop">
                                        <form id="cmtForm'.$i.'">
                                            <div class="form-group commentText">
                                                <textarea class=".cTA form-control" name="cmtText" id="tA'.$i.'" required rows="1" placeholder="'. $name .' post a comment"></textarea>
                                                <input type="hidden" value="'.$user.'" name="by">
                                                <input type="hidden" value="'.$posted_by.'" name="to">
                                                <input type="hidden" value="'.$uniqID.'" name="uniqId">
                                                <button class="btn btn-primary" id="commB'.$i.'">Send</button>
                                            </div>
                                        </form>
                                        <form id="likForm'.$i.'" style="display: inline-flex;line-height:35px;">
                                            <div class="likTexct">
                                                <input type="hidden" value="'.$user.'" name="by">
                                                <input type="hidden" value="'.$posted_by.'" name="to">
                                                <input type="hidden" value="'.$uniqID.'" name="idL">
                                                <button style="background:transparent;width:30px;height:30px;border:none;" id="likB'.$i.'"><span id="likStat'.$i.'"></span></button>
                                            </div>
                                            &nbsp&nbspLikes :  <span id="likes'.$i.'"></span>&nbsp&nbsp&nbsp&nbsp Comments :  <span id="comments'.$i.'"></span>
                                        </form>';
                                    $return .= '                                        
                                    </div>
                                    <div class="createFooterBottom" id="allComm'.$i.'"></div>
                                </div>';
                    ?>
                        <script>
                            $(document).ready(function(){
                                $.ajax({
                                    type: 'post',
                                    url: '/allComments.php',
                                    data: 'id=<?php echo $uniqID; ?>',
                                    success: function (data) {
                                        $('#allComm<?php echo $i; ?>').html(data);
                                    }
                                });
                                $.ajax({
                                    type: 'get',
                                    url: '/numCom.php?id=<?php echo $uniqID; ?>',
                                    success: function (response) {
                                        $('#comments<?php echo $i; ?>').html(response);
                                    }
                                });
                                $.ajax({
                                    type: 'get',
                                    url: '/likStat.php?id=<?php echo $uniqID; ?>',
                                    success: function (response) {
                                        $('#likStat<?php echo $i; ?>').html(response);
                                    }
                                });
                                $.ajax({
                                    type: 'get',
                                    url: '/numLikes.php?id=<?php echo $uniqID; ?>',
                                    success: function (response) {
                                        $('#likes<?php echo $i; ?>').html(response);
                                    }
                                });
                                $('#cmtForm<?php echo $i; ?>').on('submit', function(e) {
                                    e.preventDefault();
                                    $('#commB<?php echo $i; ?>').html("<i class='fa fa-spinner fa-spin'></i>");
                                    $.ajax({
                                        type: 'post',
                                        url: '/comment.php',
                                        data: $('#cmtForm<?php echo $i; ?>').serialize(),
                                        beforeSend: function(){
                                            $('#comments<?php echo $i; ?>').html("<i class='fa fa-spinner fa-spin'></i>");
                                        },
                                        success: function (response) {
                                            $('#commB<?php echo $i; ?>').delay("slow").html("Send");
                                            $('#comments<?php echo $i; ?>').load('/numCom.php?id=<?php echo $uniqID; ?>');
                                            $('#cmtForm<?php echo $i; ?>').trigger("reset");
                                            $('#allComm<?php echo $i; ?>').load('/allComments.php?id=<?php echo $uniqID; ?>');
                                        }
                                    });
                                });
                                $('#likForm<?php echo $i; ?>').on('submit', function(e) {
                                    e.preventDefault();
                                    $.ajax({
                                        type: 'post',
                                        url: '/numLikes.php',
                                        data: $('#likForm<?php echo $i; ?>').serialize(),
                                        beforeSend: function(){
                                            $('#likStat<?php echo $i; ?>').html("<i class='fa fa-spinner fa-spin'></i>");
                                        },
                                        success: function (response) {
                                            $('#liks<?php echo $i; ?>').html(response);
                                            $("#likStat<?php echo $i; ?>").load('/likStat.php?id=<?php echo $uniqID; ?>'); 
                                            $('#likes<?php echo $i; ?>').load('/numLikes.php?id=<?php echo $uniqID; ?>');
                                        }
                                    });
                                });
                                $('#delForm<?php echo $i; ?>').on('submit', function(e) {
                                    e.preventDefault();
                                    $.ajax({
                                        type: 'post',
                                        url: '/numLikes.php',
                                        data: $('#delForm<?php echo $i; ?>').serialize(),
                                        success: function (response) {
                                            window.location.reload();
                                        }
                                    });
                                });
                            });
                        </script>
                    <?php
                }
                $return .= '</div>';
                echo $return;
            }   
        }
    }

    public function getSinglePost($uniqID, $user){
        $lvUser = new User($this->conn, $user);
        $name = $lvUser->getFirstName($user);
        $sqld = mysqli_query($this->conn, "SELECT * FROM posts WHERE uniqID='$uniqID' ORDER BY id DESC");
        $return = "";
        $return = '<div class="createP2">';
        if(mysqli_num_rows($sqld) == 0){
            ?>
                <script>
                    window.location.replace("/");
                </script>
            <?php
        }
        $pNme = mysqli_fetch_assoc($sqld);
        $content = $pNme['body'];
        $uniqID = $pNme['uniqID'];
        $imageID = $pNme['images'];
        $posted_by = $pNme['posted_by'];
        $posted_to = $pNme['posted_to'];
        $body = $pNme['body'];
        $likes = $pNme['likes'];
        $comments = $pNme['comments'];
        $return = '<div class="createP2">';
        $lvPostImg = new Posts($this->conn, $posted_by);
        $return .= '<div class="createH2">' . $lvPostImg->getPostedBy($posted_by) .  $lvPostImg->getPostedTo($posted_to) . '</div>';
        if($posted_by == $user){
            $return .= '
            <form id="delForm" style="width:40px;float:right;">
                <div class="delText">
                    <input type="hidden" value="'.$user.'" name="by">
                    <input type="hidden" value="'.$posted_by.'" name="to">
                    <input type="hidden" value="'.$uniqID.'" name="idD">
                    <button style="background:transparent;width:30px;height:30px;border:none;" id="delB"><i title="Delete" class="fa fa-times" style="color:red;"></i></button>
                </div>
            </form>';
        }
        $return .= '<div class="createText" style="max-height:max-content;">' . $body . "</div>";
        $return .= $lvPostImg->getImagesSingle($imageID);
        $return .= '<div class="createFooter">
                        <div class="createFooterTop">
                            <form id="cmtForm" style="width:80%">
                                <div class="form-group commentText">
                                    <textarea class=".cTA form-control" name="cmtText" id="tA" required rows="1" placeholder="'. $name .' post a comment"></textarea>
                                    <input type="hidden" value="'.$user.'" name="by">
                                    <input type="hidden" value="'.$posted_by.'" name="to">
                                    <input type="hidden" value="'.$uniqID.'" name="uniqId">
                                    <button class="btn btn-primary" id="commB">Send</button>
                                </div>
                            </form>
                            <form id="likForm" style="display: inline-flex;line-height:35px;">
                                <div class="likText">
                                    <input type="hidden" value="'.$user.'" name="by">
                                    <input type="hidden" value="'.$posted_by.'" name="to">
                                    <input type="hidden" value="'.$uniqID.'" name="idL">
                                    <button style="background:transparent;width:30px;height:30px;border:none;" id="likB"><span id="likStat"></span></button>
                                    &nbsp&nbspLikes :  <span id="likes"></span>&nbsp&nbsp&nbsp&nbsp Comments :  <span id="comments"></span>
                                </div>
                            </form>';
                            $return .= '  
                        </div>
                        <div class="createFooterBottom" id="allComm"></div>
                    </div>';
        ?>
            <script>
                $(document).ready(function(){
                    $.ajax({
                        type: 'post',
                        url: '/allComments.php',
                        data: 'id=<?php echo $uniqID; ?>',
                        success: function (data) {
                            $('#allComm').html(data);
                        }
                    });
                    $.ajax({
                        type: 'get',
                        url: '/numCom.php?id=<?php echo $uniqID; ?>',
                        success: function (response) {
                            $('#comments').html(response);
                        }
                    });
                    $.ajax({
                        type: 'get',
                        url: '/likStat.php?id=<?php echo $uniqID; ?>',
                        success: function (response) {
                            $('#likStat').html(response);
                        }
                    });
                    $.ajax({
                        type: 'get',
                        url: '/numLikes.php?id=<?php echo $uniqID; ?>',
                        success: function (response) {
                            $('#likes').html(response);
                        }
                    });
                    $('#cmtForm').on('submit', function(e) {
                        e.preventDefault();
                        $('#commB').html("<i class='fa fa-spinner fa-spin'></i>");
                        $.ajax({
                            type: 'post',
                            url: '/comment.php',
                            data: $('#cmtForm').serialize(),
                            beforeSend: function(){
                                $('#comments').html("<i class='fa fa-spinner fa-spin'></i>");
                            },
                            success: function (response) {
                                $('#commB').delay("slow").html("Send");
                                $('#comments').load('/numCom.php?id=<?php echo $uniqID; ?>');
                                $('#comForm').trigger("reset");
                                $('#allComm').load('/allComments.php?id=<?php echo $uniqID; ?>');
                            }
                        });
                    });
                    $('#likForm').on('submit', function(e) {
                        e.preventDefault();
                        $.ajax({
                            type: 'post',
                            url: '/numLikes.php',
                            data: $('#likForm').serialize(),
                            beforeSend: function(){
                                $('#likStat').html("<i class='fa fa-spinner fa-spin'></i>");
                            },
                            success: function (response) {
                                $('#liks').html(response);
                                $("#likStat").load('/likStat.php?id=<?php echo $uniqID; ?>'); 
                                $('#likes').load('/numLikes.php?id=<?php echo $uniqID; ?>');
                            }
                        });
                    });
                    $('#delForm').on('submit', function(e) {
                        e.preventDefault();
                        $.ajax({
                            type: 'post',
                            url: '/numLikes.php',
                            data: $('#delForm').serialize(),
                            success: function (response) {
                                window.location.replace('/');
                            }
                        });
                    });
                });
            </script>
        <?php
        $return .= '</div>';
        echo $return;
    }


}
