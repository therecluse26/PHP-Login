<?php
$pagetype = 'loginpage';
require_once '../autoload.php';
require_once '../vendor/autoload.php';
$conf = new GlobalConf;

//Pull username, generate new ID and hash password
$userid = $_POST['userid'];
$pw1 = $_POST['password1'];
$pw2 = $_POST['password2'];

$userjson = "[".json_encode($userid)."]";

try {

    $user = UserData::userDataPull($userjson, 0);

    $pwresp = PasswordPolicy::validate($pw1, $pw2, $conf->pwpolicy, $conf->pwminlength);

    //Validation passed
    if ($pwresp['status'] == true) {

        //Tries inserting into database and add response to variable

        $a = new PasswordForm;

        $response = $a->resetPw($user[0]['id'], $pw1);

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

} catch (Exception $e) {

    echo $e->getMessage();
};
