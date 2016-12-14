<?php
function checkSessionKey($key) {
    if (!isset($_SESSION[$key])) {
        return false;
    }
    return $_SESSION[$key];
}

if (($pagetype != 'loginpage') && !array_key_exists('username', $_SESSION) || (array_key_exists('username', $_SESSION) && $ip != getenv ("REMOTE_ADDR") || (checkSessionKey('admin') == false && $pagetype == 'adminpage'))) {
    session_destroy();
    header("location:".$url."/login/index.php");
    exit;

} else if (array_key_exists('username', $_SESSION) && $pagetype == 'loginpage') {

    header("location:".$url."/index.php");   


} 