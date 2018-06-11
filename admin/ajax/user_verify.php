<?php
/**
* AJAX page for user verification in userverification.php
**/
try {
    require '../../vendor/autoload.php';

    session_start();

    $request = new PHPLogin\CSRFHandler;
    $auth = new PHPLogin\AuthorizationHandler;
    $conf = new PHPLogin\AppConfig;

    if ($request->valid_token() && ($auth->isSuperAdmin() || $auth->hasPermission('Verify Users'))) {
        $config = PHPLogin\AppConfig::pullMultiSettings(array("base_url", "base_dir", "curl_enabled"));

        $uid = $_POST['uid'];

        $userarr = PHPLogin\UserData::userDataPull($uid, 0);

        try {
            //Updates the verify column on user
            $vresponse = PHPLogin\UserHandler::verifyUser($userarr, 1);

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

                if ($config['curl_enabled'] == 'true') {
                    //shell_exec enabled
                    shell_exec('curl '.$config['base_url'].'/login/ajax/emailqueue.php?usr='.$userurlparm.'  > /dev/null 2>/dev/null &');
                } else {
                    //shell_exec is disabled
                    include $config['base_dir'].'/login/ajax/emailqueue.php';
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
        http_response_code(401);
        throw new Exception("Unauthorized");
    }
} catch (Exception $e) {
    echo $e->getMessage();
}
