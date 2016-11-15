<?php
$cwd = getcwd(); // remember the current path
chdir('../../');
require 'autoload.php';
require 'class/functions.php';
require 'config.php';

//Pulls variables from url. Can pass 1 (verified) or 0 (unverified/blocked) into url
$uid = $_GET['uid'];



if(isset($uid) && strlen(trim($uid)) > 20 && strlen(trim($uid)) < 25){

    $e = new UserData;
    $eresult = $e->userDataPull($uid, 0);

    $email = $eresult['email'];
    $username = $eresult['username'];

    $d = new Delete;

    if (isset($uid) && !empty(str_replace(' ', '', $uid))) {

        //Updates the verify column on user
        $dresponse = $d->deleteUser($uid);

        //Success
        if ($dresponse == 'true') {
            echo $activemsg;

        } else {
            //Echoes error from MySQL
            echo $dresponse;
        }
    } else {
        //Validation error from empty form variables
        echo 'An error occurred... click <a href="index.php">here</a> to go back.';
    };
}


