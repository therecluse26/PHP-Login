<?php
/**
* AJAX page for log deletion in maillog.php
**/
try {
    require '../../login/autoload.php';

    session_start();

    $request = new CSRFHandler;
    $auth = new AuthorizationHandler;

    if ($request->valid_token() && $auth->isAdmin()) {
        //Pulls variables from url. Can pass 1 (verified) or 0 (unverified/blocked) into url

        $uid = $_POST['uid'];

        $ids = json_decode($uid);

        if ((isset($ids)) && sizeof($ids) >= 1) {
            try {
                foreach ($ids as $id) {

                //Deletes user
                    $dresponse = EmailLogger::deleteLog($id);

                    //Success
                    if ($dresponse['status'] == 1) {
                        echo json_encode($dresponse);
                    } else {
                        //Validation error from empty form variables
                        //header('HTTP/1.1 400 Bad Request');
                        throw new Exception("Failure");
                    }
                }
            } catch (Exception $ex) {
                echo json_encode(array("status"=>0,"message"=>$ex->getMessage()));
            }
        }
    } else {
        http_response_code(401);
        throw new Exception("Unauthorized");
    }
} catch (Exception $e) {
    echo $e->getMessage();
}
