<?php
require "../autoload.php";
$conf = new GlobalConf;
require $conf->base_dir."/login/vendor/autoload.php";

use \Firebase\JWT\JWT;

//$conf = new GlobalConf;

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

    $cookie = CookieHandler::generateCookie($cookieid, $userid, $tokenid, $token, $conf->cookie_expire);

} catch (Exception $e) {
    echo $e->getMessage();
}
