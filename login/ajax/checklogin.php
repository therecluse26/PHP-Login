<?php
//'true' triggers login success
ob_start();
require '../../vendor/autoload.php';

try {
    // Define $myusername and $mypassword
    $username = $_POST['myusername'];
    $password = $_POST['mypassword'];

    // To protect MySQL injection
    $username = stripslashes($username);
    $password = stripslashes($password);

    $response = '';
    $loginCtl = new PHPLogin\LoginHandler;
    $lastAttempt = $loginCtl->checkAttempts($username);
    $max_attempts = PHPLogin\AppConfig::pullSetting("max_attempts", "unsigned");

    //First Attempt
    if ($lastAttempt['lastlogin'] == '') {
        $lastlogin = 'never';
        $loginCtl->insertAttempt($username);
        $response = $loginCtl->checkLogin($username, $password);
    } elseif ($lastAttempt['attempts'] >= $max_attempts) {

      //Exceeded max attempts
        $loginCtl->updateAttempts($username);
        $response = $loginCtl->checkLogin($username, $password);
    } else {
        $response = $loginCtl->checkLogin($username, $password, $_POST['remember']);
    };

    if ($lastAttempt['attempts'] < $max_attempts && $response != 'true') {
        $loginCtl->updateAttempts($username);
        $jsonResp = json_encode(['username'=>$username, 'response'=>$response]);
        echo $jsonResp;
    } else {
        $jsonResp = json_encode(['username'=>$username, 'response'=>$response]);
        echo $jsonResp;
    }

    unset($resp, $jsonResp);
    ob_end_flush();
} catch (Exception $e) {
    error_log($e->getMessage());
    echo json_encode($e->getMessage());
}
