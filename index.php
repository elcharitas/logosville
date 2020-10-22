<title>Logosville</title>
	<script src="/ckeditor/ckeditor.js"></script>
	<script src="/ckeditor/samples/js/sample.js"></script>
	<link rel="stylesheet" href="/ckeditor/samples/css/samples.css">
	<link rel="stylesheet" href="/ckeditor/toolbarconfigurator/lib/codemirror/neo.css">
<?php
    include("inc/header.php");
    $createErrorMsg = "";
    $text = "";
    $fMsg = "";
    $imageId = "";
    if(isset($_POST['writePost'])){
        $date = date("Y-m-d");
        $uniqID = $lvOnline.strtotime($date).rand();
        $text = $_POST['createBody'];
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
        if( !empty($text) && mysqli_query($conn, "INSERT INTO posts(`uniqID`, `body`, `images`, `videos`, `feelings`, `posted_by`, `posted_to`, `comments`, `likes`, `views`, `deleted`) VALUES ('$uniqID', '$text','$imageId','-','0','$lvOnline','','0','0','0','no')")){
            $fMsg = '<div class="text-success">Post Uploaded</div>';
            ?>
                <script>
                    alert("Post Uploaded");
                    window.location.replace("/");
                </script>
            <?php
        } else if(empty($text)){
            $fMsg .= '<div class="text-danger">Post body required</div>';
        }
    }
?>
<section class="main">
    <div class="margin large"></div>
    <div class="bodyMain container-fluid" style="margin-left: 0;padding-left:0;">
        <div class="row">
            <div class="col-lg-1 lMain"></div>
            <div class="col-lg-8 col-md-8 col-sm-12 col-12">
                <div class="card createP" style="border-radius: 12px;">
                    <p class="card-header createH">Publish Content</p>
                    <div class="card-body" style="padding: 5px 0;">
                        <form action="/" method="POST" id="createForm" enctype="multipart/form-data">
                            <div class="createTA">
                                <div class="form-group row">
                                    <div class="col-12">
                                        <textarea class="no-border form-control" id="editor" required name="createBody" rows="2" placeholder="What's on your mind, <?php echo $lvUser->getLastname($lvOnline); ?>?"><?php echo $text; ?></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="createTA2">
                                <div class="row">
                                    <div class="col-2 createOthers"></div>
                                    <div class="col-2 createOthers">
                                        <div>
                                            <label for="file" class="crL">
                                                <img src="/assets/images/photo.png" style="width:15px;height:15px;" > Photos
                                            </label>
                                            <input type="file" class="form-control-file" name="file[]" id="file" multiple hidden>
                                        </div>
                                    </div>
                                    <div class="col-3 createOthers">
                                        <div id="result"></div>
                                    </div>
                                    <div class="col-6"></div>
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
                <?php echo $lvPosts->recentPosts($lvOnline); ?>
            </div>
        </div>
    </div>
    <?php include("fixedSide.php"); ?>
</section>
<?php
    include("inc/footer.php");
?>
<style>
    .no-border{
        font-size: 22px;
        border:none;
    }

    .no-border:focus{
        border: transparent;
        box-shadow: 0 0 0 .2rem rgba(0,123,255, 0);
    }
    .crL{
        display:inline-flex;
        font-size:10px;
        background:#f5f6f7;
        padding:9px;
        border-radius:15px;
        font-weight: 600;
    }
    .crL:hover{
        background-color: #adb4bb;
    }
</style>
<script>
    $(document).ready(function(){
        $('#tedit').click(function(){
            alert("LLL");
            $(".tedit").attr("id", "editor");
        });
    /*
        $('#createForm').submit(function(e) {
            e.preventDefault();   

            $.ajax({
                type: 'post',
                url: 'post.php',
                data: $('#createForm').serialize(),
                success: function (response) {
                    alert(response);
                }
            });

            // i have try withe post methode and that the same
        /** $.post('creer.php', $(a).serialize(), function (data) {
                $('#myModal').modal('show');
                $(".mydivinfo").html(data);
            }).error(function() {
                // This is executed when the call to mail.php failed.
            });**

            //alert(infos);

            return false 
        });  */
    });
</script>
<script>
    initSample();
</script>