<?php
/**
* AJAX page for testing email settings in editconfig.php
**/
try {
    require '../../login/autoload.php';

    session_start();

    $request = new CSRFHandler;
    $auth = new AuthorizationHandler;

    if ($request->valid_token() && $auth->isSuperAdmin()) {
        if ($_GET['t'] == '1') {
            $test = new MailHandler;

            $resp = $test->testMailSettings();

            unset($test);

            if ($resp['status'] == 'false') {
            }

            $jsonresp = json_encode($resp);

            echo $jsonresp;
        }
    } else {
        throw new Exception("Unauthorized");
    }
} catch (Exception $e) {
    echo json_encode($e->getMessage());
}
