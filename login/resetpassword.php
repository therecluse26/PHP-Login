<?php
$userrole = 'loginpage';
$title = 'Reset Password';
require 'misc/pagehead.php';
require '../vendor/autoload.php';
?>

<script src="js/resetpw.js"></script>
<script src="js/jquery.validate.min.js"></script>
<script src="js/additional-methods.min.js"></script>

</head>
<body>

  <?php require 'misc/pullnav.php'; ?>

    <div class="container logindiv">
        <div class="col-sm-4"></div>
        <div class="col-sm-4">
<?php

$jwt = $_GET['t'];

try {
    $decoded = Firebase\JWT\JWT::decode($jwt, $conf->jwt_secret, array('HS256'));

    $email = $decoded->email;
    $tokenid = $decoded->tokenid;
    $userid = $decoded->userid;
    $pw_reset = $decoded->pw_reset;

    $validToken = PHPLogin\TokenHandler::selectToken($tokenid, $userid, 0);

    if ($validToken && ($decoded->pw_reset == "true")) {
        require "partials/resetform.php";
    } else {
        echo "<br><br><div class='alert alert-danger alert-dismissable'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>Invalid or expired token, please resubmit <a href='".$conf->base_url."/login/forgotpassword.php'>forgot password form</a></div><div id='returnVal' style='display:none;'>false</div>";
    };
} catch (Exception $e) {
    echo "<br><br><div class='alert alert-danger alert-dismissable'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>".$e->getMessage()."<br>Token failure, try re-sending request <a href='".$conf->base_url."/login/forgotpassword.php'>here</a></div><div id='returnVal' style='display:none;'>false</div>";
}
?>
<div id="message"></div>
</div>
<div class="col-sm-4"></div>
</div>
</body>
