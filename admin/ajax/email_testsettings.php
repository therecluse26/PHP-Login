<?php
/**
* AJAX page for testing email settings in config.php
**/
try {
    require '../../vendor/autoload.php';

    session_start();

    $request = new PHPLogin\CSRFHandler;
    $auth = new PHPLogin\AuthorizationHandler;

    if ($request->valid_token() && $auth->isSuperAdmin()) {
        if ($_GET['t'] == '1') {
            $test = new PHPLogin\MailHandler;

            $resp = $test->testMailSettings();

            unset($test);

            if ($resp['status'] == 'false') {
            }

            $jsonresp = json_encode($resp);

            echo $jsonresp;
        }
    } else {
        http_response_code(401);
        throw new Exception("Unauthorized");
    }
} catch (Exception $e) {
    echo json_encode($e->getMessage());
}
