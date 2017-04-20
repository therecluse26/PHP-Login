<?php
//DO NOT ECHO ANYTHING ON THIS PAGE OTHER THAN RESPONSE
//'true' triggers reset success
ob_start();
include 'config.php';
require 'includes/functions.php';

$email = $_POST['email'];

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>We\'re sorry but the email provided was not a valid email address</div>';
}else{
  $resetCtl = new ResetForm;

  if ($resetCtl->checkExists($email) == true) {
    echo '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>We\'re sorry but that email is not in our system</div>' . $resetCtl->checkExists($email);
  }else {
    $response = $resetCtl->reset($email);

    if (substr($response, 0, -9) == 'true') {

        echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Your password has been reset and you should recieve an email shortly</div>';

        $newpassword = substr($response, -8);

        $m = new MailSender;
        $m->sendMail($email, $resetCtl->getUser($email)['username'], $newpassword, 'Reset');
    }else {
      echo $response;
    }
  }
}
