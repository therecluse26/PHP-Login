<?php
/**
* AJAX page for user verification in userverification.php
**/
try {
    require '../../login/autoload.php';

    session_start();

    $request = new CSRFHandler;
    $auth = new AuthorizationHandler;

    if ($request->valid_token() && $auth->isAdmin()) {
        $conf = AppConfig::pullMultiSettings(array("base_url", "base_dir", "curl_enabled"));

        //Pulls variables from url. Can pass 1 (verified) or 0 (unverified/blocked) into url
        $uid = $_POST['uid'];

        $userarr = UserData::userDataPull($uid, 0);

        try {
            //Updates the verify column on user
            $vresponse = UserHandler::verifyUser($userarr, 1);

            //Success
            if ($vresponse['status'] == true) {
                echo $vresponse['status'];

                $userser = serialize($userarr);
                $user64 = base64_encode($userser);
                $userurlparm = urlencode($user64);

                //Send to email queue (to run in background)
                function func_enabled($function)
                {
                    $disabled = explode(',', ini_get('disable_functions'));
                    return !in_array($function, $disabled);
                }

                if ($conf['curl_enabled'] == 'true') {
                    //shell_exec enabled
                    shell_exec('curl '.$conf['base_url'].'/login/ajax/emailqueue.php?usr='.$userurlparm.'  > /dev/null 2>/dev/null &');
                } else {
                    //shell_exec is disabled
                    include $conf['base_dir'].'/login/ajax/emailqueue.php';
                }
            } else {
                //Validation error from empty form variables
                header('HTTP/1.1 400 Bad Request');
                throw new Exception($vresponse['message']);
            }
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    } else {
        throw new Exception("Unauthorized");
    }
} catch (Exception $e) {
    echo $e->getMessage();
}
