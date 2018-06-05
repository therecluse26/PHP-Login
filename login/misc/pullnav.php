<?php
//Pulls navbar
if (array_key_exists('username', $_SESSION)) {
    $conf->pullNav($_SESSION['username']);

    //Checks for proper mailer configuration. Only checks connection if email_working db entry is false
    if ($conf->email_working == 'false') {
        $mailtest = new PHPLogin\MailHandler;

        $mailresult = $mailtest->testMailSettings();

        if ($mailresult['status'] == 'true') {
            $conf->updateMultiSettings(array("email_working"=>"true"));
        } else {
            echo "<div class=\"alert alert-danger alert-dismissable\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>Server Error: Please contact site administrator at <a href='mailto:".$conf->admin_email."?subject=Login%20System%20Error&body=Error%20Code%20-%20xER41300'>".$conf->admin_email."</a> and relay this error - xER41300 </div>";
        }
    }
} else {
    $conf->pullNav();
}
