<?php
/**
* AJAX page for user deletion in userverification.php
**/
require '../../login/autoload.php';

session_start();

if ((new AuthorizationHandler)->pageOk("adminpage")) {
    $uid = $_GET['uid'];

    $eresult = UserData::userDataPull($uid, 0);

    foreach ($eresult as $e) {
        try {
            $singleId = $e['id'];

            //Deletes user
            $dresponse = UserHandler::deleteUser($singleId);

            //Success
            if ($dresponse == 1) {
                echo $dresponse;
            } else {
                //Validation error from empty form variables
                //header('HTTP/1.1 400 Bad Request');
                throw new Exception("Failure");
            }
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }
}
