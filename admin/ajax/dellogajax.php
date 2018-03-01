<?php
/**
* AJAX page for log deletion in maillog.php
**/
$cwd = getcwd(); // remember the current path
chdir('../../');
require 'login/autoload.php';

session_start();

if ((new AuthorizationHandler)->pageOk("superadminpage")) {

    //Pulls variables from url. Can pass 1 (verified) or 0 (unverified/blocked) into url

    $uid = $_GET['uid'];

    $ids = json_decode($uid);

    if ((isset($ids)) && sizeof($ids) >= 1) {

        try {

            foreach($ids as $id){

                //Deletes user
                $dresponse = Delete::deleteLog($id);

                //Success
                if ($dresponse['status'] == 1) {

                    echo json_encode($dresponse);

                    } else {
                        //Validation error from empty form variables
                        //header('HTTP/1.1 400 Bad Request');
                        throw new Exception("Failure");
                }
            }

        } catch(Exception $ex) {

            echo json_encode(array("status"=>0,"message"=>$ex->getMessage()));

        }

    }
}
