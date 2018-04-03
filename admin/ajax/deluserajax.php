<?php
/**
* AJAX page for user deletion in userverification.php
**/
require '../../login/autoload.php';
try {
    session_start();

    $request = new CSRFHandler;
    $auth = new AuthorizationHandler;

    if ($request->valid_token() && $auth->isAdmin()) {
        $uid = $_POST['uid'];

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
    } else {
        http_response_code(401);
        throw new Exception("Unauthorized");
    }
} catch (Exception $e) {
    echo $e->getMessage();
}
