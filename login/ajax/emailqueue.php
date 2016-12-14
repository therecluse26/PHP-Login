<? 
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
