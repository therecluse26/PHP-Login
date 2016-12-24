<?php
require "../autoload.php";
$conf = new AppConfig;
require $conf->base_dir."/login/vendor/autoload.php";

use \Firebase\JWT\JWT;

$uid = $_SESSION['uid'];

try {
    $user = UserData::pullUserById($uid);

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

    $cookie = CookieHandler::generateCookie($cookieid, $userid, $tokenid, $token, (int)$conf->cookie_expire_seconds);


} catch (Exception $e) {
    echo $e->getMessage();
}
