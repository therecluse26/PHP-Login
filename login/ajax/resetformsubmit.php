<?php
$userrole = 'loginpage';
require '../../vendor/autoload.php';

//Pull username, generate new ID and hash password
$jwt = $_POST['t'];
$pw1 = $_POST['password1'];
$pw2 = $_POST['password2'];

try {
    $secret = PHPLogin\AppConfig::pullSetting("jwt_secret");
    $decoded = Firebase\JWT\JWT::decode($jwt, $secret, array('HS256'));
    $userid = $decoded->userid;
    $tokenid = $decoded->tokenid;

    $validToken = PHPLogin\TokenHandler::selectToken($tokenid, $userid, 0);
} catch (Exception $e) {
    echo "Unknown error occured [AJ14440]";
    exit();
}

try {
    if ($validToken && ($decoded->pw_reset == "true")) {
        // Pulls settings
        $config = PHPLogin::pullMultiSettings(array("password_policy_enforce", "password_min_length", "signup_thanks", "base_url"));

        // Pulls user by ID
        $user = PHPLogin\UserHandler::pullUserById($userid);

        // Checks password against validation rules
        $pwresp = PHPLogin\PasswordHandler::validatePolicy($pw1, $pw2, (bool) $config['password_policy_enforce'], (int) $config['password_min_length']);

        //Validation passed
        if ($pwresp['status'] == true) {

            //Tries inserting into database and add response to variable

            $a = new PHPLogin\PasswordHandler;

            $response = $a->resetPw($user['id'], $pw1);

            //Success
            if ($response['status'] == true) {
                echo json_encode($response);
            } else {
                //Failure
                PHPLogin\MiscFunctions::mySqlErrors($response['message']);
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
