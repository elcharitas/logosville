<!DOCTYPE html>
<?php
    include("inc/header.php");
    $msg = "";
    if(isset($_GET['other'])){
        $other = $_GET['other'];
    }
    if(isset($_POST['msgBody'])){
        $msgBdy = $_POST['msgBody'];
        mysqli_query($conn, "INSERT INTO `inner_chat`(`user_from`, `user_to`, `message`) VALUES ('$lvOnline','$other','$msgBdy')");
    }
?>
<script src="https://cdn.tiny.cloud/1/1cbdjearauc9oiqvd71re9dyarko81oqaig7vl0au3w1n2ss/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"></script>
<title>Messages | Logosville </title>
<section class="main">
    <div class="margin large"></div>
    <div class="container">
        <div class="row">
            <?php 
            if(isset($_GET['other'])){
                mysqli_query($conn, "UPDATE `inner_chat` SET `seen_status`='yes', `received_status`='yes' WHERE `user_to`='$lvOnline' AND `user_from`='$other' ");
                ?>
                    <div class="col-lg-2 col-md-1"></div>
                    <div class="col-lg-8 col-md-10 col-sm-12 col-12">
                        <div class="row">
                            <div class="col-lg-12">
                                <div id="allMM"></div>
                                <form method="POST" id="contMsg" style="width: 100%;position:relative;">
                                    <input type="hidden" name="user_to" id="user_to" value="jesujuwon.oladejo">
                                    <input type="hidden" name="user_from" value="adeola.ijaduola">
                                    <div class="form-group">
                                        <div style="height: 181px;position:relative;overflow:hidden">
                                            <textarea cols="80" name="body" id="mytextarea" class="body" required rows="2" placeholder="Text"></textarea>
                                        </div>
                                    </div>
                                    <button type="submit" class="form-control btn btn-success" id="sendCMsg" title="Send Message"><i class="fa fa-paper-plane" style="transform: rotate(50deg);"></i></button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-1"></div>
                    <script>
                        tinymce.init({
                            selector: 'textarea',
                            content_css: 'material-classic',
                            skin: 'outside',
                            icons: 'material',
                            content_css_cors: true,
                            plugins: 'emoticons advcode linkchecker link autolink lists checklist powerpaste tinymcespellchecker',
                            toolbar: 'emoticons | bold italic underline superscript subscript | forecolor backcolor | align | bullist numlist checklist |',
                            toolbar_mode: 'floating',
                            toolbar_location: "bottom",
                            menubar: false
                        });
                        var other = '<?php echo $other; ?>';
                        $.ajax({
                            url: "/msg.php?convos="+other,
                            type: "GET",
                            success: function(response){
                                $('#allMM').html(response);
                            }
                        });
                        $(document).ready(function(){
                            $("#contMsg").validate({
                                rules: {
                                    body : {
                                        required: true,
                                        minlength: 3
                                    }
                                },
                                messages : {
                                    name: {
                                        minlength: "Name should be at least 3 characters"
                                    }
                                }
                            });
                        });
                            
                    </script>
                <?php
            } else{         
                $date = date("Y-M-d H:i:s");
                mysqli_query($conn, "UPDATE `inner_chat` SET `seen_status`='yes' WHERE `user_to`='$lvOnline' ");
                    ?>
                        <div class="col-lg-8 col-md-7 col-sm-12 col-12">
                            <form method="post" action="" id="newMsg">
                                <label for="my-select">To:</label>
                                <div class="form-group">
                                    <select id="my-select" class="form-control" name="user_to" required>
                                        <option class="form-control" value="">Select friend</option>
                                        <?php echo $lvMsg->listFriends($lvOnline); ?>
                                    </select>
                                </div>
                                <textarea class="form-control bodyN" name="msgBody" id="editor1" rows="3" required></textarea>
                                <div class="form-group">
                                    <button type="submit" class="form-control btn btn-success" id="sendMsg" title="Send Message"><i class="fa fa-paper-plane" style="transform: rotate(50deg);"></i></button>
                                </div>
                            </form>
                            <div class="col-lg-8 col-md-7 col-sm-12 col-12"><ul id="allM"></ul></div>
                        </div>
                    <?php
                }
            ?>
        </div>
    </div>
</section>
<div class="chatSidebar" style="box-shadow: 1px 0 0 #f0f0f2 inset;border-left-color: #ccc;">
    <div class="margin"></div>
    <div>
        <div>
            <div class="box">
                <div class="sIB">
                    <div class="clearfix">
                        <div class="sH">CONTACTS ONLINE</div>
                    </div>
                </div>
            </div>
            <ul class="box">
                <?php
                    echo $lvFriend->activeFriends($lvOnline);
                ?>
            </ul>
        </div>
        <div>
            <div class="box">
                <div class="sIB">
                    <div class="clearfix">
                        <div class="sH">CONTACTS</div>
                    </div>
                </div>
            </div>
            <ul class="box">
                <?php
                    echo $lvFriend->listFriends($lvOnline);
                ?>
            </ul>
        </div>
    </div>
    <div class="margin"></div>
</div>
<div class="buddyListPage" style="display: block;">
    <div class="buddyNum">
        <div class="numListBtn">
            <div class="numText">
                <span class="label">Chat(<i id="noAU"></i>)</span>
                <div>
                    <a href="/messages?new"  class="label" title="New Message" style="color:#33334c;line-height:16px;margin-left: 30px">New Message</a>
                </div>
            </div>
        </div>
    </div>
</div>
<link rel="stylesheet" href="/assets/css/styles.css">
<?php
    include("inc/footer.php");
?>
<style>
    @media only screen and (max-width: 576px){
        .lMain2{
            display: none;
        }
    }
    #allMM{
        margin-top: 25px;
        min-height: 50px;
        max-height: 400px;
        overflow-y: scroll;
        background-color: white;
        padding: 5px 12px;
    }
</style>
<script>
    $(document).ready(function(){
        window.setInterval(function(){
            $.ajax({
                url: "/act.php",
                type: "GET",
                success: function(response){
                    $('#noAU').html(response);
                }
            });
            $.ajax({
                url: "/msg?allM",
                type: "GET",
                success: function(response){
                    $('#allM').html(response);
                }
            });
        }, 10000);
        $.ajax({
            url: "/act.php",
            type: "GET",
            success: function(response){
                $('#noAU').html(response);
            }
        });
        $.ajax({
            url: "/msg?allM",
            type: "GET",
            success: function(response){
                $('#allM').html(response);
            }
        });
        $("#newMsg").on("submit", function(e){
            e.preventDefault();
            var body = $(".bodyN").val();
            $.ajax({
                data: $("#newMsg").serialize(),
                url:'/msg',
                type: 'POST',
                beforeSend: function(){
                    $("#sendMsg").html('<i class="fa fa-spinner fa-spin"></i>');
                },
                success: function(response){
                    $("#sendMsg").html('<i class="fa fa-paper-plane" style="transform: rotate(50deg);"></i>');
                    $(".bodyN").text("");
                    $('#allM').load("/msg?allM");
                }
            });
        });        
    });
</script>
<style>
    #edit {
      border: 3px inset grey;
      height: 100px;
      width: 381px;
      margin: 10px auto 0;
     }
    fieldset {
      margin: 2px auto 15px;
      width: 358px;
    }
    .buttons {
      width: 5ex;
      text-align: center;
      padding: 1px 3px;
    }
form, p {
  margin: 20px;
}

p.note {
  font-size: 1rem;
  color: red;
}

input, textarea {
  border-radius: 5px;
  border: 1px solid #ccc;
  padding: 4px;
  font-family: 'Lato';
  width: 300px;
  margin-top: 10px;
}

label {
  width: 300px;
  font-weight: bold;
  display: inline-block;
  margin-top: 20px;
}

label span {
  font-size: 1rem;
}

label.error {
    color: red;
    font-size: 1rem;
    display: block;
    margin-top: 5px;
}

input.error, textarea.error {
    border: 1px dashed red;
    font-weight: 300;
    color: red;
}

[type="submit"], [type="reset"], button, html [type="button"] {
    margin-left: 0;
    border-radius: 0;
    background: black;
    color: white;
    border: none;
    font-weight: 300;
    padding: 10px 0;
    line-height: 1;
}
</style>