<?php
/**
* AJAX page for user banning
**/
try {
    require '../../login/autoload.php';

    session_start();

    $request = new CSRFHandler;
    $auth = new AuthorizationHandler;

    if ($request->valid_token() && $auth->isAdmin()) {
        if (isset($_POST['uid']) && isset($_POST['ban_hours']) && isset($_POST['ban_reason'])) {
            $uid = $_POST['uid'];
            $ban_hours = $_POST['ban_hours'];
            $ban_reason = $_POST['ban_reason'];
        } else {
            throw new Exception("Missing data");
        }

        $eresult = UserData::userDataPull($uid, 0);

        foreach ($eresult as $e) {
            $singleId = $e['id'];

            //Deletes user
            $dresponse = UserHandler::banUser($singleId, $ban_hours, $ban_reason);

            //Success
            if ($dresponse == 1) {
                echo $dresponse;
            } else {
                //Validation error from empty form variables
                //header('HTTP/1.1 400 Bad Request');
                throw new Exception("Failure");
            }
        }
    } else {
        throw new Exception("Unauthorized");
    }
} catch (Exception $ex) {
    echo $ex->getMessage();
}
