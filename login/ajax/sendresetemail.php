<?php
$userrole = 'loginpage';
$title = 'Forgot Password';
require '../../vendor/autoload.php';

use \Firebase\JWT\JWT;

$email = $_POST['email'];
$resp = array();

$config = PHPLogin\AppConfig::pullMultiSettings(array("jwt_secret","base_url"));

try {
    $user = PHPLogin\UserHandler::pullUserByEmail($email);

    if (!$user) {
        throw new Exception("No user found!");
    }

    $secret = $config["jwt_secret"];
    $tokenid = uniqid('t_', true);
    $intltime = time();
    $nbftime = $intltime + 5;

    //Token expires after 1 day
    $exptime = $nbftime + 86400;

    //Data passed in JWT
    $payload = array(
        "iss" => $config["base_url"],
        "nbf" => $nbftime,
        "exp" => $exptime,
        "tokenid"=>$tokenid,
        "userid" => $user['id'],
        "email" => $user['email'],
        "username" => $user['username'],
        "pw_reset" => "true"
    );

    $jwt = JWT::encode($payload, $secret);

    $reset_url = $config["base_url"]."/login/resetpassword.php?t=".$jwt;

    $tokenInsert = PHPLogin\TokenHandler::replaceToken($user['id'], $tokenid, 0);

    if ($tokenInsert) {
        //Mail reset link w/token to user
        $mail = new PHPLogin\MailHandler;

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
