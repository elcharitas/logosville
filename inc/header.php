<!DOCTYPE html>
<script src="/assets/js/jquery.min.js"></script>
<?php
    session_start();
    if(isset($_SESSION['lvOnline'])){
        if($_SESSION['lvOnline'] == ''){
            ?>
                <script>
                    window.location.replace("/login");
                </script>
            <?php   
        } else {
            $lvOnline = $_SESSION['lvOnline'];
            ?>
                <script>
                    $(document).ready(function() {
                        window.setInterval(function(){
                            /// call your function here
                            var lvOnline = '<?php echo $lvOnline; ?>';
                            $.ajax({
                                url: "/inc/on.php",
                                data: "lvOnline=" + lvOnline,
                                type: "POST"
                            });
                        }, 10000);
                        var lvOnline = '<?php echo $lvOnline; ?>';
                        $.ajax({
                            url: "/inc/on.php",
                            data: "lvOnline=" + lvOnline,
                            type: "POST"
                        });
                    });
                </script>
            <?php
        }
    } else {
        ?>
            <script>
                window.location.replace("/login");
            </script>
        <?php   
    }
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
    $url = $_SERVER['REQUEST_URI'];


    function isMobile () {
        return is_numeric(strpos(strtolower($_SERVER['HTTP_USER_AGENT']), "mobile"));
    }

    /* If you are redirecting the user to a mobile page, it is as simple as
    if (isMobile()) {
        header("Location: /mob/");
    }*/
?>
<html lang="en" id="logosville">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/assets/css/theme.css">
    <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
    <link rel="shortcut icon" href="/assets/images/logo.jpg" type="image/x-icon">
    <script src="/assets/js/node.js"></script>
    <script src="/assets/js/jquery.min.js"></script>
    <script src="https://unpkg.com/@popperjs/core@2"></script>
    <script src="https://unpkg.com/@popperjs/core@2/dist/umd/popper.js"></script>
    <link rel="stylesheet" href="/assets/css/all.css">
    <style>
        .row{
            margin: 0;
            padding: 0;
        }
        /* Toggle this class - hide and show the create */
        .show {
            visibility: visible;
            -webkit-animation: fadeIn 1s;
            animation: fadeIn 1s;
        }
    </style>
</head>
<body>
    <section class="header large">
        <div class="h-tot row">
            <div class="h-left col-1 text-right">
                <span class="logo">
                    <a href="/">
                        <img src="/assets/images/logo.jpg" class="logo" title="Logosville">
                    </a>
                </span>
            </div>
            <div class="h-left col-5 text-left">
                <div class="search">
                    <form action="/search/top" method="get">
                        <button value="1" class="sBtn" style="color: #d7bcb3;" aria-label="Search" tabindex="-1" data-testid="facebar_search_button" type="submit">
                            <i class="sImg"></i>
                        </button>
                        <div class="uiTypeahead sInf">
                            <div class="wrap">
                                <div class="innerWrap">
                                    <div class="textInput">
                                        <input type="text" class="sInp" name="q" value="" autocomplete="off" placeholder="Search">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="h-left col-5 text-left">
                <div class="h-right">
                    <a class="h-href <?php if($url == '/u/'. $lvOnline){echo 'active';} ?>" href="/u/<?php echo $lvOnline; ?>" title="Profile">
                        <span class="">
                            <img src="<?php echo $lvUser->getDp($lvOnline); ?>" class="h-p-user" alt="">
                        </span>
                        <span class="" style="margin-left: 5px;"><?php echo $lvUser->getLastName($lvOnline); ?></span>
                    </a>
                    <span class="rBdr"></span>
                    <a class="h-href <?php if($url == '/'){echo 'active';} ?>" href="/" title="Logosville.com">
                        <span class="">
                            Home
                        </span>
                    </a>
                    <span class="rBdr"></span>
                    <a class="icon2" title="Friend requests">
                        <span class="req " onclick="request()">
                            <span class="frL" id="frL"></span>
                        </span>
                    </a>
                    <div class="reqtext " id="myReq">
                        <ul class="reqUl" role="menu">
                            <li class="reqLi" role="presentation">
                                <span class="reqTop">
                                    <span class="float-left">Friend requests</h4>
                                </span>
                            </li>
                            <li class="reqLi2 style-1" role="presentation">
                                <ul>
                                    <?php echo $lvFriend->friendRequests($lvOnline); ?>
                                </ul>
                            </li>
                            <li class="reqLi" role="presentation">
                                <span class="reqFooter">
                                    <a href="/requests" class="text-center">See All</a>
                                </span>
                            </li>
                        </ul>
                    </div>
                    <a class="icon2" title="Messages">
                        <span class="msg _1qv9" id="msB" onclick="msg()">
                            <span class="msgL" id="msgL"></span>
                        </span>
                    </a>
                    <div class="msgtext " id="myMsg">
                        <ul class="reqUl" role="menu">
                            <li class="reqLi" role="presentation">
                                <span class="reqTop">
                                    <span class="float-left">Messages</h4>
                                </span>
                            </li>
                            <li class="reqLi2 style-1" role="presentation">
                                <ul class="fim"></ul>
                            </li>
                            <li class="reqLi" role="presentation">
                                <span class="reqFooter">
                                    <a href="/messages" class="text-center">See All</a>
                                </span>
                            </li>
                        </ul>
                    </div>
                    <a class="icon2" title="Notification">
                        <span class="req _1qv9" id="notB" onclick="notify()">
                            <span class="notL" id="notL"></span>
                        </span>
                    </a>
                    <span class="rBdr"></span>
                    <div class="nottext " id="myNot">
                        <ul class="reqUl" role="menu">
                            <li class="reqLi" role="presentation">
                                <span class="reqTop">
                                    <span class="float-left">Notifications</h4>
                                    <a class="float-right mar-right-2" id="mAR">Mark All As Read</a>
                                </span>
                            </li>
                            <li class="reqLi2 style-1" role="presentation">
                                <ul class="fica" id="notBdy"></ul>
                            </li>
                            <li class="reqLi" role="presentation">
                                <span class="reqFooter">
                                    <a href="/notifications" class="text-center">See All</a>
                                </span>
                            </li>
                        </ul>
                    </div>
                    <a class="icon2" href="/sign_out">
                        <span class="req _1qv9">
                            <i class="fa fa-2x fa-sign-out-alt text-danger"></i>
                        </span>
                    </a>
                </div>
            </div>
        </div>
    </section>
    <section class="header2 small">
        <div class="row">
            <div class="h-left col-1 text-right">
                <span class="logo">
                    <a href="/">
                        <img src="/assets/images/logo.jpg" class="logo" title="Logosville">
                    </a>
                </span>
            </div>
            <div class="h-left col-9 text-left">
                <div class="search">
                    <form action="/search/top" method="get">
                        <button value="1" class="sBtn" style="color: #d7bcb3;" aria-label="Search" tabindex="-1" data-testid="facebar_search_button" type="submit">
                            <i class="sImg"></i>
                        </button>
                        <div class="uiTypeahead sInf">
                            <div class="wrap">
                                <div class="innerWrap">
                                    <div class="textInput">
                                        <input type="text" class="sInp" name="q" value="" autocomplete="off" placeholder="Search">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="h-left col-2 text-center">
                <a href="/sign_out">
                    <i class="fa fa-sign-out-alt fa-2x text-danger" style="margin-top: 2px;"></i>
                </a>
            </div>
            <div class="menu" id="menu">
                <ul>
                    <a class="<?php if($url == '/'){echo 'actives';} ?>" href="/">Home</a>
                    <a class="<?php if($url == '/u/'. $lvOnline){echo 'actives';} ?>" href='/u/<?php echo $lvOnline; ?>'><?php echo $lvUser->getLastName($lvOnline); ?></a>
                    <a class="<?php if($url == '/messages'){echo 'actives';} ?>" href="/messages">Messages<?php echo $lvMsg->myMessages($lvOnline); ?></a>
                    <a class="<?php if($url == '/friends'){echo 'actives';} ?>" href="/friends">Friends <span id="frSM"></span> </a>
                    <a class="<?php if($url == '/notifications'){echo 'actives';} ?>" href="/notifications">Notifications<span id="notSM"></span></a>
                </ul>
            </div>
        
        </div> 
    </section>
    <style>
        .menu{
            margin: 0 auto;
            margin-top: 10px;
            min-width: 400px;
        }
        .menu > ul{
            display: inline-flex;
        }
        .menu > ul > a{
            color: white;
            padding: 5px 12px;
        }
        .menu > ul > a:hover{
            background-color: rgb(0,0,0, 0.2);
        }
        .actives{
            background-color: rgb(0,0,0, 0.6);
        }
    </style>
    <script>
         $(document).ready(function() {
            /// call your function here
            var lvOnline = '<?php echo $lvOnline; ?>';
            $.ajax({
                url: "/noticeAjax.php?reqOwn=" + lvOnline,
                type: "GET",
                success: function(response){
                    $("#frL").html(response);
                    $("#frSM").html(response);
                }
            });

            window.setInterval(function(){
                /// call your function here
                $.ajax({
                    url: "/noticeAjax.php",
                    data: "notiOwn=" + lvOnline,
                    type: "POST",
                    success: function(response){
                        $("#notL").html(response);
                    }
                });
                $.ajax({
                    url: "/noticeAjax.php?notiOwn=" + lvOnline,
                    data: "notiOwn=" + lvOnline,
                    type: "GET",
                    success: function(response){
                        $("#notL").html(response);
                        $("#notSM").html(response);
                    }
                });
                $.ajax({
                    url: "/noticeAjax.php?reqOwn=" + lvOnline,
                    type: "GET",
                    success: function(response){
                        $("#frL").html(response);
                        $("#frSM").html(response);
                    }
                });
            }, 10000);
            $.ajax({
                url: "/noticeAjax.php?notiOwn=" + lvOnline,
                data: "notiOwn=" + lvOnline,
                type: "GET",
                success: function(response){
                    $("#notL").html(response);
                    $("#notSM").html(response);
                }
            });
            $("#notB").on("click", function() {
                $.ajax({
                    url: "/noticeAjax.php",
                    data: "notiOwn2=" + lvOnline,
                    type: "POST",
                    success: function(response){
                        $("#notL").load("/noticeAjax.php?notiOwn=" + lvOnline);
                        $('#notBdy').delay("250000").html('<i class="fa fa-spinner fa-spin"></i>');
                        $("#notBdy").delay("2500000").load("/noticeAjax.php?notBDY=" + lvOnline);
                    }
                });
            });
            $("#mAR").on("click", function() {
                $.ajax({
                    url: "/noticeAjax.php",
                    data: "marker=" + lvOnline,
                    type: "POST",
                    success: function(response){
                        $('#notBdy').delay('slow').html('<i class="fa fa-spinner fa-spin"></i>');
                        $("#notBdy").load("/noticeAjax.php?notBDY=" + lvOnline);
                    }
                });
            });
            $("#msB").on("click", function() {
                $.ajax({
                    url: "/noticeAjax.php",
                    data: "marker=" + lvOnline,
                    type: "POST",
                    success: function(response){
                        $('.fim').delay('slow').html('<i class="fa fa-spinner fa-spin"></i>');
                        $(".fim").load("/noticeAjax.php?msgBDY=" + lvOnline);
                    }
                });
            });
        });
    </script>
    <style>
        #mAR:hover{
            cursor: pointer;
        }
        .img{
            border-radius: 100%;
            border: none;
            width: 32px;
            height: 32px;
        }
    </style>