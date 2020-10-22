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
<link rel="stylesheet" href="/assets/css/styles.css">
<script src="https://cdn.tiny.cloud/1/1cbdjearauc9oiqvd71re9dyarko81oqaig7vl0au3w1n2ss/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"></script>
<title>Messages | Logosville </title>
<section class="main">
    <div class="margin large"></div>
    <div class="container">
        <div class="row">
            <?php 
            if(isset($_GET['other'])){
                ?>
                    <div class="col-lg-2 col-md-1"></div>
                    <div class="col-lg-8 col-md-10 col-sm-12 col-12">
                        <div class="row">
                            <div class="col-lg-12">
                                <div id="allMM"></div>
                                <form method="POST" id="theForm" style="width: 100%;position:relative;">
                                    <input type="hidden" name="user_from" value="<?php echo $lvOnline; ?>">
                                    <input type="hidden" name="user_to" value="<?php echo $other; ?>">
                                    <textarea class="form-control body" id="body" name="contM" required rows="2" placeholder="Text"></textarea>
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
                            $("#sendCMsg").on("click", function(e) {
                                e.preventDefault();
                                var content = tinymce.get("body").getContent({format: 'html'});
                                var user_from = "<?php echo $lvOnline; ?>";
                                var user_to = "<?php echo $other; ?>";
                                if($.trim(content) != ''){
                                    $.ajax({
                                        url: "/msg",
                                        type: "POST",
                                        data: $("#theForm").serialize() + "&body=" + content,
                                        beforeSend: function(){

                                        },
                                        success: function(){
                                            $('#allMM').load("/msg.php?convos=" + user_to);
                                            $("#body").html("");
                                            $('#theForm').trigger("reset");
                                        }
                                    });
                                } else {
                                    $('.body').css("border", "1px solid Red");

                                    return false;
                                }
                            });
                            if(tinymce.get("body").is(":focus")){
                                alert("Active");
                            }
                            else{
                                //no focus for input and textarea
                            } 
                            window.setInterval(function(){
                                $.ajax({
                                    url: "/msg.php?convos="+other,
                                    type: "GET",
                                    success: function(response){
                                        $('#allMM').html(response);
                                    }
                                });
                            }, 10000);
                        });
                    </script>
                <?php
            }else{
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
                            <textarea class="form-control body" name="body" rows="3" required></textarea>
                            <div class="form-group">
                                <button type="submit" class="form-control btn btn-success" id="sendMsg" title="Send Message"><i class="fa fa-paper-plane" style="transform: rotate(50deg);"></i></button>
                            </div>
                        </form>
                        <div class="col-lg-8 col-md-7 col-sm-12 col-12"><ul id="allM"></ul></div>
                    </div>
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
                        $(document).ready(function(){
                            $("#sendMsg").on("click", function(e) {
                                e.preventDefault();
                                var content = tinymce.get("body").getContent({format: 'html'});
                                var user_from = "<?php echo $lvOnline; ?>";
                                var user_to = "<?php echo $other; ?>";
                                if($.trim(content) != ''){
                                    $.ajax({
                                        url: "/msg",
                                        type: "POST",
                                        data: $("#newMsg").serialize() + "&body=" + content,
                                        beforeSend: function(){

                                        },
                                        success: function(){
                                            $('#allMM').load("/msg.php?convos=" + user_to);
                                            $("#body").html("");
                                            $('#newMsg').trigger("reset");
                                        }
                                    });
                                } else {
                                    $('.body').css("border", "1px solid Red");

                                    return false;
                                }
                            });
                            window.setInterval(function(){
                                $.ajax({
                                    url: "/msg.php?convos="+other,
                                    type: "GET",
                                    success: function(response){
                                        $('#allMM').html(response);
                                    }
                                });
                            }, 50000);
                        });
                    </script>
                <?php
            }
            ?>
        </div>
    </div>
</section>
<?php
    include("inc/footer.php");
?>
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
    $('#allMM').animate({ scrollTop: $('#allMM').scrollHeight}, "slow");
</script>
<style>
    #edit {
      border: 3px inset grey;
      height: 100px;
      width: 381px;
      margin: 10px auto 0;
     }
    .buttons {
        width: 5ex;
        text-align: center;
        padding: 1px 3px;
    }
</style>