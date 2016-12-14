<?php
    $pagetype = 'loginpage';
    $title = 'Reset Password';
    require 'partials/pagehead.php';
    require 'vendor/autoload.php';
?>
</head>
<body>
    <div class="container logindiv">

<div class="col-sm-4"></div>
<div class="col-sm-4">

<?php

$jwt = $_GET['t'];

$conf = new GlobalConf;

$secret = $conf->jwt_secret;

use \Firebase\JWT\JWT;

try {

$decoded = JWT::decode($jwt, $secret, array('HS256'));

$email = $decoded->email;
$tokenid = $decoded->tokenid;
$userid = $decoded->userid;
$pw_reset = $decoded->pw_reset;

$validToken = TokenHandler::selectToken($tokenid, $userid, 0);


    if($validToken && ($decoded->pw_reset == "true")){

        require "partials/resetform.php";

    }
    else {

        echo "Invalid or expired token, please resubmit <a href='".$conf->base_url."/login/forgotpassword.php'>forgot password form</a>";
    };

//print_r($decoded);

} catch (Exception $e) {

    echo $e->getMessage()."<br>Token failure, try re-sending request <a href='".$conf->base_url."/login/forgotpassword.php'>here</a>";

}

?>

<div id="message"></div>

</div>

<div class="col-sm-4"></div>

</div>
</body>



