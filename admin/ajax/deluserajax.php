<?php
/**
* AJAX page for user deletion in verifyusers.php
**/
$cwd = getcwd(); // remember the current path
chdir('../../');
require 'login/autoload.php';

session_start();

if ((new AuthorizationHandler)->pageOk("adminpage")) {

    //Pulls variables from url. Can pass 1 (verified) or 0 (unverified/blocked) into url

    $uid = $_GET['uid'];

    $ids = MiscFunctions::assembleUids($uid);

    if ((isset($ids)) && sizeof($ids) >= 1) {

        $e = new UserData;
        $eresult = $e->userDataPull($uid, 0);

        foreach($eresult as $e) {

            try {

                $singleId = $e['id'];

                //Deletes user
                $dresponse = Delete::deleteUser($singleId);

                //Success
                if ($dresponse == 1) {

                    echo $dresponse;

                    } else {
                        //Validation error from empty form variables
                        //header('HTTP/1.1 400 Bad Request');
                        throw new Exception("Failure");
                }

            } catch(Exception $ex) {
                echo $ex->getMessage();

            }
        }
    }
}
