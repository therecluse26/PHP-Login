<?php
/**
* AJAX page for saving configuration in editconfig.php
**/
require "../../login/autoload.php";
try {
    session_start();
    $conf = new AppConfig;
    $request = new CSRFHandler;
    $auth = new AuthorizationHandler;

    if ($request->valid_token() && $auth->isSuperAdmin()) {
        unset($_POST['csrf_token']);
        $update = $conf->updateMultiSettings($_POST);

        echo json_encode($update);
    } else {
        throw new Exception("Unauthorized");
    }
} catch (Exception $e) {
    error_log($e->getMessage());
    echo json_encode($e->getMessage());
}
