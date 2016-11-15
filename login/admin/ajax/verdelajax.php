<?php
$cwd = getcwd(); // remember the current path
chdir('../../');
require 'autoload.php';
require 'class/functions.php';
require 'config.php';

//Pulls variables from url. Can pass 1 (verified) or 0 (unverified/blocked) into url
$uid = $_GET['uid'];
$method = $_GET['m'];

if($method == 'ver'){
    $verify = 1;
}
elseif($method == 'del'){
    $delete = 1;
}
else{
    unset($verify, $delete);
}

if(isset($verify)){
    $e = new UserData;
    $eresult = $e->userDataPull($uid, 0);

    $email = $eresult['email'];
    $username = $eresult['username'];

    $v = new Verify;

    if (isset($uid) && !empty(str_replace(' ', '', $uid)) && isset($verify) && !empty(str_replace(' ', '', $verify))) {

        //Updates the verify column on user
        $vresponse = $v->verifyUser($uid, $verify);

        //Success
        if ($vresponse == 'true') {
            echo $activemsg;

            //Send verification email
            $m = new MailSender;
            $m->sendMail($email, $username, $uid, 'Active');

        } else {
            //Echoes error from MySQL
            header('HTTP/1.1 400 Bad Request');
            trigger_error('Errroooorrrr');
            //echo $vresponse;
        }
    } else {
        //Validation error from empty form variables
        echo 'An error occurred... click <a href="index.php">here</a> to go back.';
    };

} elseif(isset($delete)){
    $e = new UserData;
    $eresult = $e->userDataPull($uid, 0);

    $email = $eresult['email'];
    $username = $eresult['username'];

    $d = new Delete;

    if (isset($uid) && !empty(str_replace(' ', '', $uid)) && isset($delete) && !empty(str_replace(' ', '', $delete))) {

        //Updates the verify column on user
        $dresponse = $d->deleteUser($uid);

        //Success
        if ($dresponse == 'true') {
            echo $activemsg;

            //Send verification email
            $m = new MailSender;
            $m->sendMail($email, $username, $uid, 'Active');
        } else {
            //Echoes error from MySQL
            echo $dresponse;
        }
    } else {
        //Validation error from empty form variables
        echo 'An error occurred... click <a href="index.php">here</a> to go back.';
    };
}


