<?php
if(!isset($_SESSION)) 
{ 
    session_start(); 
} 

if (array_key_exists('username', $_SESSION) && $_SESSION['ip_address'] == getenv ( "REMOTE_ADDR" )) {
  session_start();
  session_destroy();
}

$pagetype = 'loginpage';
$title = 'Reset Password';
require 'autoload.php';
require 'partials/pagehead.php';
require 'vendor/autoload.php';


$id = $_GET['id'];
$userinf = new UserData;
$conf = new GlobalConf;

$key = $conf->jwt_secret;

//$jwt = $_GET['token'];

use \Firebase\JWT\JWT;

//****This would go in "forgot password" link... send out using email

try {
    $user = UserData::userDataPull($id, 0)['username'];

    if(sizeof($user) == 0){
        throw new Exception("No username found!");
    }

    $token = array(
    "iss" => $conf->base_url,
    //"email" => $email,
    "username" => $user,
    "pw_reset" => "true"
    );

    $jwt = JWT::encode($token, $key);

    //$jwt = $_GET['jwt'];

    JWT::$leeway = 60; // $leeway in seconds

    //echo $jwt . '<br>';

    //**** end of "forgot password" link code

    //Decoding token (error handling)
    try {
        $decoded = JWT::decode($jwt, $key, array('HS256'));
        //print_r($decoded);

        if($decoded->pw_reset == "true"){

            //Includes Reset Form
            include "resetform.php";

        }
        else {
            echo "Reset FORBIDDEN!";
        };

    }
    catch (Exception $e){
        echo $e->getMessage();
    }

}
catch (Exception $f){
    echo $f->getMessage();
}



