<?php
    $servername = "localhost";
    $username = "aposjggd_admin";
    $password = "Logosville2020";
    $dbname = 'aposjggd_one';
    
    // Create connection
    $conn = mysqli_connect($servername, $username, $password, $dbname);
    
    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
?>