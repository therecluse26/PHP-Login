<?php
/**
* Called via shell_exec; sends mass emails in the background
*/
if (isset($_GET["usr"])) {
    require "../../vendor/autoload.php";

    $userarr = unserialize(base64_decode(urldecode($_GET["usr"])));

    try {
        $m = new PHPLogin\MailHandler;
        $m->sendMail($userarr, "Active");
    } catch (Exception $e) {
        trigger_error($e->getMessage());
        die();
    }

    exit;
} else {
    $userarr = unserialize(base64_decode($userurlparm));

    try {
        $m = new PHPLogin\MailHandler;
        $m->sendMail($userarr, "Active");
    } catch (Exception $e) {
        trigger_error($e->getMessage());
        die();
    }
}
