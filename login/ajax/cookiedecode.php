<?php
require $conf->base_dir."/vendor/autoload.php";

use \Firebase\JWT\JWT;

$resp = array();

try {
    $cookie = PHPLogin\CookieHandler::decodeCookie("usertoken");

    if ($cookie['status'] == true) {
        $cookiedata = JWT::decode($cookie["value"], $conf->jwt_secret, array('HS256'));

        $cookieDbPull = PHPLogin\CookieHandler::validateCookie($cookiedata->userid, $cookiedata->cookieid, $cookiedata->tokenid, (int)$conf->cookie_expire_seconds);

        if (!isset($cookieDbPull['tokenid'])) {
            session_destroy();
            unset($_COOKIE['usertoken']);
            echo "<div class=\"alert alert-danger alert-dismissable\">
                    <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>
                    Cookie has changed! You may be under attack! Log out and change your password immediately</div>";
        } else {

            //Valid cookie
            $user = PHPLogin\UserHandler::pullUserById($cookieDbPull['userid']);
            $_SESSION['uid'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['ip_address'] = $_SERVER['REMOTE_ADDR'];
        }
    }

    unset($userinf);
} catch (Exception $e) {
    echo $e->getMessage();
}
