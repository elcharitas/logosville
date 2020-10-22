<?php
    include("dbh.inc.php");
    $createErrorMsg = "";
    $text = "";
    if(isset($_POST['writePost'])){
        $text = $_POST['createBody'];
        if(empty($text)){
            $createErrorMsg .= '<div class="text-danger">Post body required</div>';
        }
        else if(count($_FILES['file']['name']) > 0){
            if(count($_FILES['file']['name']) > 5){
                $createErrorMsg .= '<div class="text-danger">Max number of files is 5</div>';
            } else {
                $imageId = rand();
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
                            $createErrorMsg .= '<div class="text-danger">'.$shortname.' is an invalid file type</div>';
                        } else {
                            //save the url and the file
                            $filePath = "/assets/images/posts/" . rand() . date('d-m-Y-H-i-s').'.'.$ext;

                            //Upload the file into the temp dir
                            if(move_uploaded_file($tmpFilePath, $filePath)) {

                                $files[] = $shortname;
                                //insert into db 
                                //use $shortname for the filename
                                //use $filePath for the relative url to the file

                            }
                        }
                    }
                }
            }
        }

        //show success message
        echo "<h1>Uploaded:</h1>";    
        if(is_array($files)){
            echo "<ul>";
            foreach($files as $file){
                echo "<li>$file</li>";
            }
            echo "</ul>";
        }
    }
    if($createErrorMsg == "") {
        $createErrorMsg .= '<div class="text-success">Post Uploaded</div>';
        mysqli_query($conn, "INSERT INTO po(`body`) VALUES ('$text')");
    }
echo $createErrorMsg . " " . $text;
mysqli_query($conn, "INSERT INTO po(`body`) VALUES ('$text')");

?>