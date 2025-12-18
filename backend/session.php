<?php 
//this file is used to check for session expiration and log out the user if inactive for too long
function checkSession ($path) {
    $expireAfter = 30; 

    if (isset($_SESSION['last_action'])) {
        $secondsInactive = time() - $_SESSION['last_action'];
        $expireAfterSeconds = $expireAfter * 60;

        if ($secondsInactive >= $expireAfterSeconds) {
            session_unset();
            session_destroy();
            header("Location:" . $path);
            exit();
        }
    }
    $_SESSION['last_action'] = time();
}

?>