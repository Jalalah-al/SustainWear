<?php 
function checkSession ($path) {
    $expireAfter = 30; // in minutes

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