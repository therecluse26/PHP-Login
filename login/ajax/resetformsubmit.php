<?php
$pagetype = 'loginpage';
require_once '../autoload.php';
require_once '../vendor/autoload.php';
$config = new AppConfig;

//Pull username, generate new ID and hash password
$userid = $_POST['userid'];
$pw1 = $_POST['password1'];
$pw2 = $_POST['password2'];

try {

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

} catch (Exception $e) {

    echo $e->getMessage();
};
