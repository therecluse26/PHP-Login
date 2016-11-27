<?php
$cwd = getcwd(); // remember the current path
chdir('../../');
require 'login/config.php';
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
                if ($vresponse == 1) {

                    echo $vresponse;

                    $userser = serialize($userarr);
                    $user64 = base64_encode($userser);
                    $userurlparm = urlencode($user64);
                    
                    //Send to email queue (to run in background)
                    $mailQueue = shell_exec('curl '.$base_url.'/login/ajax/emailqueue.php?usr='.$userurlparm.'  > /dev/null 2>/dev/null &');

                    trigger_error($mailQueue);

                    } else {
                        //Validation error from empty form variables
                        header('HTTP/1.1 400 Bad Request');
                        throw new Exception("Failure");
                } 

            } catch(Exception $ex) {
                echo $ex->getMessage();
                
            }

} 



