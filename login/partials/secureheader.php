<?php
function checkSessionKey($key)
{
    if (!isset($_SESSION[$key])) {
        return false;
    }
    return $_SESSION[$key];
}

if (($pagetype != 'loginpage') && !array_key_exists('username', $_SESSION) || (array_key_exists('username', $_SESSION) && $ip != getenv ("REMOTE_ADDR") || (checkSessionKey('admin') == false && $pagetype == 'adminpage'))) {

    $refurl = urlencode("http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");

    session_destroy();
    header("location:".$url."/login/index.php?refurl=".$refurl);
    exit;

} elseif (array_key_exists('username', $_SESSION) && $pagetype == 'loginpage') {

    if (array_key_exists('refurl', $_GET)){

        //Goes to referred url
        $refurl = urldecode($_GET['refurl']);
        header("location:".$refurl);

    } else {

        header("location:".$url."/index.php");
    }
}
