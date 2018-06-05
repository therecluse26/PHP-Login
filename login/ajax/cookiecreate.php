<?php
require $conf->base_dir."/vendor/autoload.php";

use \Firebase\JWT\JWT;

$uid = $_SESSION['uid'];

try {
    $user = PHPLogin\UserHandler::pullUserById($uid);

    if (!$user) {
        throw new Exception("No user found!");
    }

    $secret = $conf->jwt_secret;
    $tokenid = uniqid('t_', true);
    $cookieid = uniqid();
    $userid = $user['id'];
    //Data passed in JWT
    $payload = array(
        "iss" => $conf->base_url,
        "tokenid"=>$tokenid,
        "cookieid"=>$cookieid,
        "userid" => $userid,
    );

    $token = JWT::encode($payload, $secret);

    $cookie = PHPLogin\CookieHandler::generateCookie($cookieid, $userid, $tokenid, $token, (int)$conf->cookie_expire_seconds);
} catch (Exception $e) {
    echo $e->getMessage();
}
