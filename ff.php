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
    if(isset($_GET['profileO'])){
        $profileO = $_GET['profileO'];
        if($profileO != $lvOnline){
            if($lvFriend->isFriend($profileO, $lvOnline)){
                ?>
                    <form method="POST" id="unfriend">
                        <input type="text" name="user_to" value="<?php echo $profileO; ?>" hidden>
                        <input type="text" name="user_from" value="<?php echo $lvOnline; ?>" hidden>
                        <input type="text" name="unfriend" hidden>
                        <button type="submit" title="Unfriend User" class="btn btn-danger"><i class="fa fa-user-minus"></i></button>
                    </form>
                <?php
            } else if($lvFriend->reqRec($lvOnline, $profileO)){
                ?>
                    <form method="POST" id="cancel">
                        <input type="text" name="user_to" value="<?php echo $profileO; ?>" hidden>
                        <input type="text" name="user_from" value="<?php echo $lvOnline; ?>" hidden>
                        <input type="text" name="cancel" hidden>
                        <button type="submit" title="Cancel Friend Request" class="btn btn-secondary"><i class="fa fa-user-times"></i></button>
                    </form>
                <?php
            } else if($lvFriend->reqSent($lvOnline, $profileO)){
                ?>
                    <div style="display: inline-flex;">
                        <form method="POST" id="accept">
                            <input type="text" name="user_from" value="<?php echo $profileO; ?>" hidden>
                            <input type="text" name="user_to" value="<?php echo $lvOnline; ?>" hidden>
                            <input type="text" name="accept" hidden>
                            <button type="submit" title="Accept request" class="btn btn-success"><i class="fa fa-user-plus"></i></button>
                        </form>
                        <form method="POST" id="decline" style="margin-left: 20px;">
                            <input type="text" name="user_from" value="<?php echo $profileO; ?>" hidden>
                            <input type="text" name="user_to" value="<?php echo $lvOnline; ?>" hidden>
                            <input type="text" name="decline" hidden>
                            <button type="submit" title="Decline Request" class="btn btn-danger" id="decBtn" style="background-color: red;"><i class="fa fa-user-alt-slash"></i></button>
                        </form>
                    </div>
                    
                <?php
            } else {
                ?>
                    <form method="POST" id="addFriend">
                        <input type="text" name="user_to" value="<?php echo $profileO; ?>" hidden>
                        <input type="text" name="user_from" value="<?php echo $lvOnline; ?>" hidden>
                        <input type="text" name="addFriend" hidden>
                        <button type="submit" name="addFriend" title="Add Friend" class="btn btn-primary"><i class="fa fa-user-plus"></i></button>
                    </form>
                <?php
            }
        }
    }
?>
<script>
    $(document).ready(function(){
        $("#addFriend").on("submit", function(e){
            e.preventDefault();
            $.ajax({
                url: "/friendsL.php",
                type: 'POST',
                data: $("#addFriend").serialize(),
                beforeSend:function(){
                    $("#addFriend").html('<i class="fa fa-spinner fa-spin"></i>');
                },
                success:function(response){
                    $("#req").load('/ff.php?profileO=<?php echo $profileO; ?>');
                }
            });
        });
        $("#cancel").on("submit", function(e){
            e.preventDefault();
            $.ajax({
                url: "/friendsL.php",
                type: 'POST',
                data: $("#cancel").serialize(),
                beforeSend:function(){
                    $("#cancel").html('<i class="fa fa-spinner fa-spin"></i>');
                },
                success:function(response){
                    $("#req").load('/ff.php?profileO=<?php echo $profileO; ?>');
                }
            });
        });
        $("#accept").on("submit", function(e){
            e.preventDefault();
            $.ajax({
                url: "/friendsL.php",
                type: 'POST',
                data: $("#accept").serialize(),
                beforeSend:function(){
                    $("#accept").html('<i class="fa fa-spinner fa-spin"></i>');
                },
                success:function(response){
                    $("#req").load('/ff.php?profileO=<?php echo $profileO; ?>');
                }
            });
        });
        $("#decline").on("submit", function(e){
            e.preventDefault();
            $.ajax({
                url: "/friendsL.php",
                type: 'POST',
                data: $("#decline").serialize(),
                beforeSend:function(){
                    $("#decline").html('<i class="fa fa-spinner fa-spin"></i>');
                },
                success:function(response){
                    $("#req").load('/ff.php?profileO=<?php echo $profileO; ?>');
                }
            });
        });
        $("#unfriend").on("click", function(e){
            e.preventDefault();
            $.ajax({
                url: "/friendsL.php",
                type: 'POST',
                data: $("#unfriend").serialize(),
                beforeSend:function(){
                    $("#unfriend").html('<i class="fa fa-spinner fa-spin"></i>');
                },
                success:function(response){
                    $("#req").load('/ff.php?profileO=<?php echo $profileO; ?>');
                }
            });
        });
    });
</script>