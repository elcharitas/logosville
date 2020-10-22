<?php
    include("inc/header.php");
    if(isset($_GET['id'])){
        $uniqID = $_GET['id'];
    } else {
        ?>
            <script>
                window.location.replace("/");
            </script>
        <?php
    }
    $read = "no";
?>
<title>Posts | Logosville </title>
<section class="main">
    <div class="margin"></div>
    <div class="container" style="margin-left: 0;padding-left:0;">
        <?php echo $lvPosts->getSinglePost($uniqID, $lvOnline); ?>
    </div>
</section>
<?php
    include("inc/footer.php");
?>