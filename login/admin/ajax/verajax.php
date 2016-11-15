<?php
$cwd = getcwd(); // remember the current path
chdir('../../');
require 'autoload.php';
require 'class/functions.php';
require 'config.php';

//Pulls variables from url. Can pass 1 (verified) or 0 (unverified/blocked) into url

$uid = $_GET['uid'];

$ids = assembleUids($uid);

if (isset($ids) && sizeof($ids) >= 1) {

    $e = new UserData;
    $eresult = $e->userDataPull($uid, 0);

    var_dump($eresult);

    $email = $eresult['email'];
    $username = $eresult['username'];

    $v = new Verify;

    //Updates the verify column on user
    $vresponse = $v->verifyUser($ids, 1);

    //Success
    if ($vresponse == 1) {

        echo $vresponse;

        //Send verification email
        $m = new MailSender;
        //SEND MAIL $m->sendMail($email, $username, $uid, 'Active');

    } else {
        //Echoes error from MySQL
        header('HTTP/1.1 400 Bad Request');
        echo $vresponse;
    }
} else {
    //Validation error from empty form variables
    header('HTTP/1.1 400 Bad Request');
    echo 'Failure';
};



