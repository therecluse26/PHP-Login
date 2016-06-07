<?php
//DO NOT ECHO ANYTHING ON THIS PAGE OTHER THAN RESPONSE
//'true' triggers login success
ob_start();
session_start();
include 'config.php';
require 'scripts/class.loginscript.php';

// Define $myusername and $mypassword
$username = $_POST['myusername'];
$password = $_POST['mypassword'];

// To protect MySQL injection
$username = stripslashes($username);
$password = stripslashes($password);

$response = '';

$loginCtl = new LoginForm;
$conf = new GlobalConf;
$lastAttempt = checkAttempts($username);
$max_attempts = $conf->max_attempts;


//First Attempt
if($lastAttempt['lastlogin'] == ''){
    $lastlogin = 'never';
    $loginCtl->insertAttempt($username);
    $response = $loginCtl->checkLogin($username, $password);
}
else if ($lastAttempt['attempts'] >= $max_attempts){
    //Exceeded max attempts
    $loginCtl->updateAttempts($username);
    $response = $loginCtl->checkLogin($username, $password);

}
else
{
    //echo 'else';
    $lastlogin = $lastAttempt['lastlogin'];
    $response = $loginCtl->checkLogin($username, $password);
};


if($lastAttempt['attempts'] < $max_attempts && $response != 'true'){
    $loginCtl->updateAttempts($username);

    $resp = new RespObj($username, $response);
    $jsonResp = json_encode($resp);
    echo $jsonResp;

}
else if ($response == 'true' && $lastAttempt['attempts'] < $max_attempts){
    $_SESSION['username'] = $username;
    $_SESSION['password'] = $password;
    $loginCtl->resetAttempts($username);

    $resp = new RespObj($username, $response);
    $jsonResp = json_encode($resp);
    echo $jsonResp;

}
else {
    $resp = new RespObj($username, $response);
    $jsonResp = json_encode($resp);
    echo $jsonResp;

}

unset($resp, $jsonResp);
ob_end_flush();
?>
