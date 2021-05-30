<?php 
    // $conn = mysqli_connect("localhost", "root", "", "chat");
    $conn = mysqli_connect("remotemysql.com", "oqMV3MXezS", "7x9nUmMGIu", "oqMV3MXezS");

    if(!$conn){
        echo "Database not connected" . mysqli_connect_error();
    }
?>