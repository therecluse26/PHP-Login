<?php
$userrole = 'loginpage';
$title = 'Verify User';
require 'misc/pagehead.php';
?>
</head>
<body>
<?php require 'misc/pullnav.php'; ?>
<div class="container">

<?php
//Pulls variables from url. Can pass 1 (verified) or 0 (unverified/blocked) into url
$uid_decoded = base64_decode($_GET['uid']);
$idarr = array($uid_decoded);
$uids = json_encode($idarr);

$userarr = PHPLogin\UserData::userDataPull($uids, 0);

try {
    //Updates the verify column on user
    $vresponse = PHPLogin\UserHandler::verifyUser($userarr, 1);

    //Success
    if ($vresponse['status'] == true) {
        echo '<form class="form-signin" action="'.$conf->signin_url.'"><div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'.$conf->active_msg.'</div><br><input type="submit" class="btn btn-lg btn-primary btn-block" value="Click Here to Log In"></button></form>';

        //Send verification email
        $m = new PHPLogin\MailHandler;

        //SEND MAIL
        $m->sendMail($userarr, 'Active');
    } else {
        //Echoes error from MySQL
        echo $vresponse['message'];
    }
} catch (Exception $ex) {
    echo $ex->getMessage();
}
?>
</div>
</body>
</html>
