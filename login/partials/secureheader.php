<?php
//Checks session keys
function checkSessionKey($key)
{
    if (!isset($_SESSION[$key])) {
        return false;
    }
    return $_SESSION[$key];
}

$allowed_types = array('superadminpage', 'adminpage', 'userpage', 'loginpage', 'page');
if (!in_array($pagetype, $allowed_types, true)) {
    throw new Exception("Server Error: Please contact site administrator and relay this error - xER41400 [".$pagetype."]");
    exit;
}

if ($ip != getenv("REMOTE_ADDR") || (checkSessionKey("admin") == false && $pagetype == "adminpage") || (checkSessionKey("username") == false && $pagetype == "userpage")) {

    $refurl = urlencode("http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");

    session_destroy();

    header("location:".$this->base_url."/login/index.php?refurl=".$refurl);

    exit;

} elseif (array_key_exists("username", $_SESSION) && $pagetype == "loginpage") {

    if (array_key_exists("refurl", $_GET)){

        //Goes to referred url
        $refurl = urldecode($_GET["refurl"]);

        header("location:".$refurl);

    } else {

        header("location:".$this->base_url."/index.php");
    }

//Prevent non superadmins from accessing superadmin pages
} elseif ($pagetype == "superadminpage") {

    if ((checkSessionKey("superadmin") == false) || $_SESSION["superadmin"] != 1) {

        header("location:".$this->base_url);
    }
}
