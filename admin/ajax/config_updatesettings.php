<?php
/**
* AJAX page for saving configuration in config.php
**/
require "../../vendor/autoload.php";
try {
    session_start();
    $conf = new PHPLogin\AppConfig;
    $request = new PHPLogin\CSRFHandler;
    $auth = new PHPLogin\AuthorizationHandler;

    if ($request->valid_token() && ($auth->isSuperAdmin() || $auth->hasPermission('Edit Site Config'))) {
        unset($_POST['csrf_token']);
        $update = $conf->updateMultiSettings($_POST);

        echo json_encode($update);
    } else {
        http_response_code(401);
        throw new Exception("Unauthorized");
    }
} catch (Exception $e) {
    error_log($e->getMessage());
    echo json_encode($e->getMessage());
}
