<?php
$cwd = getcwd(); // remember the current path
chdir('../../');
require 'login/autoload.php';

//Pulls variables from url. Can pass 1 (verified) or 0 (unverified/blocked) into url
$uid = $_GET['uid'];

$ids = MiscFunctions::assembleUids($uid);

if (isset($ids) && sizeof($ids) >= 1) {

$e = new UserData;
$userarr = $e->userDataPull($uid, 0);

    try {
        //Updates the verify column on user
        $vresponse = Verify::verifyUser($userarr, 1);

        //Success
        if ($vresponse['status'] == true) {

            echo $vresponse;

            $userser = serialize($userarr);
            $user64 = base64_encode($userser);
            $userurlparm = urlencode($user64);

            //Send to email queue (to run in background)

            function func_enabled($function) {
                $disabled = explode(',', ini_get('disable_functions'));
                return !in_array($function, $disabled);
            }

            if (func_enabled("shell_exec")) {
                //shell_exec enabled
                $mailQueue = shell_exec('curl '.AppConfig::pullSetting("base_url").'/login/ajax/emailqueue.php?usr='.$userurlparm.'  > /dev/null 2>/dev/null &');

            } else {
                //shell_exec is disabled
                include AppConfig::pullSetting("base_dir").'/login/ajax/emailqueue.php';

            }

        } else {
            //Validation error from empty form variables
            header('HTTP/1.1 400 Bad Request');
            throw new Exception($vresponse['message']);
        }

    } catch(Exception $ex) {
        echo $ex->getMessage();
    }
}



