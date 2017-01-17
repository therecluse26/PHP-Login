<?php
if ($_GET['t'] == '1'){
    require '../../login/autoload.php';
    $test = new MailSender;
    $resp = $test->testMail();
    unset($test);

    $jsonresp = json_encode($resp);
    echo $jsonresp;
}
