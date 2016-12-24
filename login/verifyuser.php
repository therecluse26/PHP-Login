<div class="container">
<?php
$pagetype = 'loginpage';
$title = 'Verify User';
require 'partials/pagehead.php';
?>

</head>
<body>

<?php
$config = new AppConfig;
$conf = $config->pullMultiSettings("signin_url", "active_msg");

//Pulls variables from url. Can pass 1 (verified) or 0 (unverified/blocked) into url
$uid_non_json = base64_decode($_GET['uid']);
$idarr = array($uid_non_json);
$uids = json_encode($idarr);

$userarr = UserData::userDataPull($uids, 0);

try {
    //Updates the verify column on user
    $vresponse = Verify::verifyUser($userarr, 1);

    //Success
    if ($vresponse == 1) {

        echo '<form class="form-signin" action="'.$conf["signin_url"].'"><div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'.$conf["activemsg"].'</div><br><input type="submit" class="btn btn-lg btn-primary btn-block" value="Click Here to Log In"></button></form>';

        //Send verification email
        $m = new MailSender;
        //SEND MAIL
        $m->sendMail($userarr, 'Active');


    } else {
        //Echoes error from MySQL
        echo $vresponse;
    }

} catch (Exception $ex) {

    echo $ex->getMessage();

}

?>
</div>
</body>
</html>
