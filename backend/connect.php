<?php

    function connectDB() {
        $host = "localhost";
        $user = "root";
        $pass = "";
        $db = "sustainwear";
        
        $conn = new mysqli($host, $user, $pass, $db);
        
        if($conn->connect_error){
            die("Failed to connect DB: " . $conn->connect_error);
        }
        
        return $conn;
    }
}
?>
