<?php
$pagetype = 'loginpage';
$title = 'Forgot Password';
require_once '../autoload.php';
require_once '../vendor/autoload.php';

use \Firebase\JWT\JWT;

$email = $_POST['email'];
$config = new AppConfig;
$resp = array();

$conf = $config->pullMultiSettings(array("jwt_secret","base_url"));

try {
    $user = UserData::pullUserByEmail($email);

    if (!$user) {

        throw new Exception("No user found!");
    }

    $secret = $conf["jwt_secret"];
    $tokenid = uniqid('t_', true);
    $intltime = time();
    $nbftime = $intltime + 5;

    //Token expires after 1 day
    $exptime = $nbftime + 86400;

    //Data passed in JWT
    $payload = array(
        "iss" => $conf["base_url"],
        "nbf" => $nbftime,
        "exp" => $exptime,
        "tokenid"=>$tokenid,
        "userid" => $user['id'],
        "email" => $user['email'],
        "username" => $user['username'],
        "pw_reset" => "true"
    );

    $jwt = JWT::encode($payload, $secret);

    $reset_url = $conf["base_url"]."/login/resetpassword.php?t=".$jwt;

    $tokenInsert = TokenHandler::replaceToken($user['id'], $tokenid, 0);

    if ($tokenInsert) {

        //Mail reset link w/token to user
        $mail = new MailSender($conf);

        $mailResult = $mail->sendResetMail($reset_url, $user['email'], $user['username']);

        $resp['status'] = 1;
        $resp['response'] = $mailResult['message'];
        echo json_encode($resp);

    }

} catch (Exception $f) {

    $resp['status'] = 0;
    $resp['response'] = $f->getMessage();
    echo json_encode($resp);

}
