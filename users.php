<?php
    include("inc/header.php");
    if(isset($_GET['username'])){
        $profileO = $_GET['username'];
        if(empty($profileO)){
            ?>
                <script>
                    window.location.replace("/");
                </script>
            <?php
        }
    }
    else {
        ?>
            <script>
                window.location.replace("/");
            </script>
        <?php
    }
    $owner = $lvUser->getFirstName($profileO);
    if(isset($_POST['upDateCover'])){
        //Get the temp file path
        $tmpFilePath = $_FILES['coverImg']['tmp_name'];

        //Make sure we have a filepath
        if($tmpFilePath != ""){
            //save the filename
            $shortname = $_FILES['coverImg']['name'];

            $ext = pathinfo($shortname, PATHINFO_EXTENSION);
            $allowed = array('gif', 'png', 'jpg', 'jpeg');
            if (!in_array($ext, $allowed)) {
                $createErrorMsg .= '<div class="text-danger">Invalid file type</div>';
            } else {
                //save the url and the file
                $filePath = "assets/images/users/" . rand() . date('d-m-Y-H-i-s').'.'.$ext;

                //Upload the file into the temp dir
                if(move_uploaded_file($tmpFilePath, $filePath)) {

                    $files[] = $shortname;
                    //insert into db 
                    //use $shortname for the filename
                    //use $filePath for the relative url to the file
                    mysqli_query($conn, "UPDATE users SET cover='/$filePath' WHERE username='$lvOnline'");
                    ?>
                        <script>
                            window.location.replace("/u/<?php echo $lvOnline; ?>");
                        </script>
                    <?php
                }
            }
        }
    }
    if(isset($_POST['upDateDP'])){
        //Get the temp file path
        $tmpFilePath = $_FILES['dpImg']['tmp_name'];

        //Make sure we have a filepath
        if($tmpFilePath != ""){
            //save the filename
            $shortname = $_FILES['dpImg']['name'];

            $ext = pathinfo($shortname, PATHINFO_EXTENSION);
            $allowed = array('gif', 'png', 'jpg', 'jpeg');
            if (!in_array($ext, $allowed)) {
                $createErrorMsg .= '<div class="text-danger">Invalid file type</div>';
            } else {
                //save the url and the file
                $filePath = "assets/images/users/dp_" . rand() . date('d-m-Y-H-i-s').'.'.$ext;

                //Upload the file into the temp dir
                if(move_uploaded_file($tmpFilePath, $filePath)) {

                    $files[] = $shortname;
                    //insert into db 
                    //use $shortname for the filename
                    //use $filePath for the relative url to the file
                    mysqli_query($conn, "UPDATE users SET dp='/$filePath' WHERE username='$lvOnline'");
                    ?>
                        <script>
                            window.location.replace("/u/<?php echo $lvOnline; ?>");
                        </script>
                    <?php
                }
            }
        }
    }
    if(isset($_POST['writePost'])){
        $date = date("Y-m-d");
        $uniqID = $lvOnline.strtotime($date).rand();
        $text = $_POST['createBody'];
        $posted_to = $_POST['posted_to'];
        if(empty($text)){
            $createErrorMsg .= '<div class="text-danger">Post body required</div>';
        }
        else{
                if(count($_FILES['file']['name']) > 0){
                if(count($_FILES['file']['name']) > 10){
                    $createErrorMsg .= '<div class="text-danger">Max number of files is 10</div>';
                } else {
                    $imageId .= $uniqID;
                    //Loop through each file
                    for($i=0; $i < count($_FILES['file']['name']); $i++) {
                        //Get the temp file path
                        $tmpFilePath = $_FILES['file']['tmp_name'][$i];

                        //Make sure we have a filepath
                        if($tmpFilePath != ""){
                            //save the filename
                            $shortname = $_FILES['file']['name'][$i];

                            $ext = pathinfo($shortname, PATHINFO_EXTENSION);
                            $allowed = array('gif', 'png', 'jpg', 'jpeg');
                            if (!in_array($ext, $allowed)) {
                                $createErrorMsg .= '<div class="text-danger">Invalid file type</div>';
                            } else {
                                //save the url and the file
                                $filePath = "assets/images/posts/" . rand() . date('d-m-Y-H-i-s').'.'.$ext;

                                //Upload the file into the temp dir
                                if(move_uploaded_file($tmpFilePath, $filePath)) {

                                    $files[] = $shortname;
                                    //insert into db 
                                    //use $shortname for the filename
                                    //use $filePath for the relative url to the file
                                    mysqli_query($conn, "INSERT INTO `images`(`imageId`, `location`, `deleted`) VALUES('$imageId', '/$filePath','no')");
                                }
                            }
                        }
                    }
                }
            }
        }
        if( !empty($text) && mysqli_query($conn, "INSERT INTO posts(`uniqID`, `body`, `images`, `videos`, `feelings`, `posted_by`, `posted_to`, `comments`, `likes`, `views`, `deleted`) VALUES ('$uniqID', '$text','$imageId','-','0','$lvOnline','$posted_to','0','0','0','no')")){
            $fMsg = '<div class="text-success">Post Uploaded</div>';
            $lvNotify->sendNotification('profile post', $lvOnline, $profileO, $uniqID);
            ?>
                <script>
                    alert("Post Uploaded");
                    window.location.replace("/u/<?php echo $profileO; ?>");
                </script>
            <?php
        } else if(empty($text)){
            $fMsg .= '<div class="text-danger">Post body required</div>';
        }
    }
    $text = "";
    $fMsg = "";
    if($profileO == $lvOnline){
        $posted_to = "";
    } else {
        $posted_to = $profileO;
    }
?>
<title><?php echo $owner; ?> | Logosville </title>

<script src="/ckeditor/ckeditor.js"></script>
<script src="/ckeditor/samples/js/sample.js"></script>
<link rel="stylesheet" href="/ckeditor/samples/css/samples.css">
<link rel="stylesheet" href="/ckeditor/samples/toolbarconfigurator/lib/codemirror/neo.css">
<section class="main">
    <div class="margin large"></div>
    <div>
        <div class="userPage">
            <div class="userCover" style="background-image: url('<?php echo $lvUser->getCover($profileO); ?>');">
                <?php
                    if($profileO == $lvOnline){
                        ?>
                            <div class="upCover" id="coverModal">
                                <form id="coverForm" method="POST" enctype="multipart/form-data">
                                    <label for="coverImg" title="Click to select cover"><i class="fa fa-camera"></i></label>
                                    <input type="file" name="coverImg" id="coverImg" accept="image/*" hidden required>
                                    <button type="submit" id="upDateCover" title="Click to upload selected cover" name="upDateCover">Upload Cover</button>
                                </form>
                            </div>
                            <div class="upDP">
                                <form id="dpForm" method="POST" enctype="multipart/form-data">
                                    <label for="dpImg" title="Click to select dp"><i class="fa fa-camera"></i></label>
                                    <input type="file" name="dpImg" id="dpImg" accept="image/*" hidden required>
                                    <button type="submit" id="upDateDP" title="Click to upload selected dp" name="upDateDP">Upload DP</button>
                                </form>
                            </div>
                        <?php
                    }
                ?>
            </div>
            <div class="userDP"></div>
            
            <div class="row">
                <span  class="text-center" style="width: 100%;"><a class=" uref" href="/u/<?php echo $profileO; ?>" >@<?php echo $profileO; ?></a></span>
                <span  class="text-center" style="width: 100%;"><?php echo $lvUser->getFullName($profileO); ?></span>
                <span class="text-center" style="width: 100%;" id="req"></span>
                <span class="text-center" style="width: 100%;">
                    <?php
                        if($profileO != $lvOnline){
                            ?>
                                <a href="/m/<?php echo $profileO; ?>"><i class="fa fa-envelope fa-2x"></i></a>
                            <?php
                        }
                    ?>
                </span>
                <iframe src='/ff.php?profileO=<?php echo $profileO; ?>' frameborder="0" style="display: none;"></iframe>
                <div class="col-lg-5 col-md-10 col-sm-12">
                    <div class="card marT50">
                        <div class="card-header">
                            Personal
                        </div>
                        <div class="card-body">
                            <p class="card-text">Firstname: <?php echo $lvUser->getFirstName($profileO); ?></p>
                            <p class="card-text">Othenames: <?php echo $lvUser->getOtherName($profileO); ?></p>
                            <p class="card-text">Surname: <?php echo $lvUser->getLastName($profileO); ?></p>
                            <p class="card-text">Gender: <?php echo $lvUser->getGender($profileO); ?></p>
                            <p class="card-text">Date of Birth: <?php echo $lvUser->getDOB($profileO); ?></p>
                        </div>
                    </div>   
                    <div class="card marT50">
                        <div class="card-header">
                            Contact
                        </div>
                        <div class="card-body">
                            <p class="card-text">Email: <?php echo $lvUser->getEmail($profileO); ?></p>
                            <p class="card-text">Phone Number: <?php echo $lvUser->getPhone($profileO); ?></p>
                            <p class="card-text">Country: <?php echo $lvUser->getCountry($profileO); ?></p>
                        </div>
                    </div>
                    <div class="card marT50">
                        <div class="card-header">
                            Friends
                        </div>
                        <div class="card-body">
                            <ul class="card-text"><?php echo $lvFriend->listFriends($profileO); ?></ul>
                        </div>
                    </div>        
                </div>         
                <div class="col-lg-7 col-md-10 col-sm-12">
                    <div class="card createP" style="border-radius: 12px;background:transparent;">
                        <p class="card-header createH">Publish Content</p>
                        <div class="card-body" style="padding: 5px 0;">
                            <form action="/u/<?php echo $profileO; ?>" method="POST" id="createForm" enctype="multipart/form-data">
                                <div class="createTA">
                                    <div class="form-group row">
                                        <div class="col-12 ">
                                            <textarea class="no-border form-control" required name="createBody" id="editor" rows="2" placeholder="What's on your mind, <?php echo $lvUser->getLastname($lvOnline); ?>?"><?php echo $text; ?></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="createTA2">
                                    <div class="row">
                                        <div class="col-2 createOthers"></div>
                                        <div class="col-4 createOthers">
                                            <div>
                                                <label for="file" class="crL">
                                                    <img src="/assets/images/photo.png" style="width:15px;height:15px;" > Photos
                                                </label>
                                                <input type="file" class="form-control-file" name="file[]" id="file" multiple hidden>
                                                <input type="hidden" name="posted_to" value="<?php echo $posted_to; ?>">
                                            </div>
                                        </div>
                                        <div class="col-3 createOthers"></div>
                                        <div class="col-12 text-center"><?php echo $fMsg; ?></div>
                                    </div>
                                </div>
                                <div class="createTA2">
                                    <div class="row">
                                        <div class="col-1"></div>
                                        <button class="btn btn-success col-10" name="writePost" type="submit" id="writePost">Publish</button>
                                        <div class="col-1"></div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card createP" style="border-radius: 12px;border: none;background:transparent;">
                        <?php echo $lvPosts->recentProPosts($profileO); ?>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</section>
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
<?php
    include("inc/footer.php");
?>
<link rel="stylesheet" href="/assets/css/styles.css">
<style>
    #req{
        margin: 10px auto;
    }
    
    .userCover{
        width: 100%;
        max-width: 800px;
        margin: auto;
        height: 400px;
        background-size: cover;
        background-position: center;
    }

    .upCover{
        padding: 5px;
        padding-left: 15px;
        width: 150px;
        border-radius: 8px;
        float: right;
        position: relative;
        margin-top: 20px;
        margin-right: 10px;
        background: rgb(255, 255, 255, 0.5);
    }

    .upDP{
        float: left;
        bottom: 10px;
        margin-top: 350px;
        margin-right: 15px;
        padding: 5px;
        padding-left: 15px;
        border-radius: 8px;
        background: rgb(255, 255, 255, 0.5);
    }

    .marT50{
        margin: 25px  auto;
        width: 98%;

    }

    .uref{
        color: #0000cd;
    }

    .uref:hover{
        color: white;
    }

    .upCover:hover,.upDP:hover, .upCover:active,.upCover::selection,.upCover:focus{
        background: white;
        cursor: pointer;
    }

    #upDateCover,#upDateDP{
        background: transparent;
        border: none;
    }

    .userDP{
        width: 200px;
        height: 200px;
        background-position: center;
        background-size: cover;
        background-image: url('<?php echo $lvUser->getDP($profileO); ?>');
        border-radius: 100%;
        border: 2px solid black;
        margin: auto;
        margin-top: -100px;
        background-color: rgb(255, 255, 255, 1);
    }

    
</style>
<script>
    $(document).ready(function(){
        $.ajax({
            url: "/ff.php?profileO=<?php echo $profileO; ?>",
            type:"get",
            success: function(response){
                $("#req").load('/ff.php?profileO=<?php echo $profileO; ?>');
            }
        });
        window.setInterval(function(){
            $.ajax({
                url: "/act.php",
                type: "GET",
                success: function(response){
                    $('#noAU').html(response);
                }
            });
        }, 60000);
        $.ajax({
            url: "/act.php",
            type: "GET",
            success: function(response){
                $('#noAU').html(response);
            }
        });
    });
</script>
<script>
	initSample();
</script>