<?
/**
Called via shell_exec; sends mass emails in the background
*/
if(isset($_GET["usr"])){

require "../autoload.php";

    $userarr = unserialize(base64_decode(urldecode($_GET["usr"])));

    try{

        $m = new MailSender;
        $m->sendMail($userarr, "Active");

    } catch (Exception $e) {

        trigger_error($e->getMessage());
        die();

    }

exit;

} else {

    $userarr = unserialize(base64_decode($userurlparm));

    try{

        $m = new MailSender;
        $m->sendMail($userarr, "Active");

    } catch (Exception $e) {

        trigger_error($e->getMessage());
        die();

    }
}
