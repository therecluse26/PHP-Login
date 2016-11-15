<?php
$page = 'verifyuser';
$title = 'Verify User';
require 'autoload.php';
require 'class/functions.php';
include 'config.php';
require 'partials/pagehead.php';

//Pulls variables from url. Can pass 1 (verified) or 0 (unverified/blocked) into url
$uid = $_GET['uid'];
$verify = $_GET['v'];

$e = new UserData;
$eresult = $e->userDataPull($uid, 0);

$email = $eresult['email'];
$username = $eresult['username'];

$v = new Verify;

if (isset($uid) && !empty(str_replace(' ', '', $uid)) && isset($verify) && !empty(str_replace(' ', '', $verify))) {

    //Updates the verify column on user
    $vresponse = $v->verifyUser($uid, $verify);

    //Success
    if ($vresponse == 1) {
        echo $activemsg;

        //Send verification email
        $m = new MailSender;
        $m->sendMail($email, $username, $uid, 'Active');
    } else {
        //Echoes error from MySQL
        echo $vresponse;
    }
} else {
    //Validation error from empty form variables
    echo 'An error occurred... click <a href="index.php">here</a> to go back.';
};

?>
</body>
</html>
