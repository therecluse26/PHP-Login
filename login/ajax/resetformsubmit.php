<?php
$pagetype = 'loginpage';
require_once '../autoload.php';
require_once '../vendor/autoload.php';
$config = new AppConfig;

//Pull username, generate new ID and hash password
$jwt = $_POST['t'];
$pw1 = $_POST['password1'];
$pw2 = $_POST['password2'];

use \Firebase\JWT\JWT;

try {
    $secret = $config->pullSetting("jwt_secret");
    $decoded = JWT::decode($jwt, $secret, array('HS256'));
    $userid = $decoded->userid;
    $tokenid = $decoded->tokenid;

    $validToken = TokenHandler::selectToken($tokenid, $userid, 0);
} catch (Exception $e) {
    echo "Unknown error occured [AJ14440]";
    exit();
}

try {

    if ($validToken && ($decoded->pw_reset == "true")) {

        $conf = $config->pullMultiSettings(array("password_policy_enforce", "password_min_length", "signup_thanks", "base_url"));

        $user = UserData::pullUserById($userid);

        $pwresp = PasswordPolicy::validate($pw1, $pw2, (bool) $conf['password_policy_enforce'], (int) $conf['password_min_length']);

        //Validation passed
        if ($pwresp['status'] == true) {

            //Tries inserting into database and add response to variable

            $a = new PasswordForm;

            $response = $a->resetPw($user['id'], $pw1);

            //Success
            if ($response['status'] == true) {

                echo json_encode($response);

            } else {
                //Failure
                MiscFunctions::mySqlErrors($response['message']);
            }

        } else {
            //Validation error from empty form variables
            echo json_encode($pwresp);
        }
    } else {
        echo "Unknown error occured [AJ14441]";
        exit();
    }

} catch (Exception $e) {

    echo $e->getMessage();
};
