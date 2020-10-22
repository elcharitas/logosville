<?php
    include("inc/header.php");
    mysqli_query($conn, "UPDATE notifications SET seen='yes' WHERE user_to='$lvOnline'");
?>
<title>Notifications | Logosville </title>
<section class="main">
    <div class="margin large"></div>
    <div class="container-fluid" style="margin:auto;">
        <div class="row">
            <div class="col-lg-1 col-md-1 col-sm-1 lMain2"></div>
            <div class="col-lg-10 col-md-10 col-sm-10 col-12">
                <div class="ficas" style="width: 100%;">
                    <?php echo $lvNotify->myNotifications($lvOnline); ?>
                </div>
            </div>
            <div class="col-lg-1 col-md-1 col-sm-1 lMain2"></div>
        </div>
    </div>
    <?php include("fixedSide.php"); ?>
</section>
<?php
    include("inc/footer.php");
?>

<style>
    @media only screen and (max-width: 576px){
        .lMain2{
            display: none;
        }
    }
    .ficas{
        list-style: none;
        width: 100%;
        max-width: 450px;
    }
    .ficas > li:hover{
        background: darkgrey;
    }
    .ficas > li{
        min-height: 50px;
        line-height: 20px;
        width: 100%;
        max-width: 450px;
    }
    .ficas > li > a{
        min-height: 50px;
        line-height: 20px;
        width: 100%;
        max-width: 450px;
        font-size: 12px;
    }

    .notiFliR{
    background-color: white;
    margin: 1px auto;
}

.notiFLiU{
    background: #bbb;
    margin: 1px auto;
}

</style>
