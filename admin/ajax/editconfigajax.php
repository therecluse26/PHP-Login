<?php
/**
* AJAX page for saving configuration in editconfig.php
**/
require "../../login/autoload.php";
$conf = new AppConfig;

session_start();

if ((new AuthorizationHandler)->pageOk("superadminpage")) {

    try {

        $update = $conf->updateMultiSettings($_POST);

        echo json_encode($update);

    } catch (Exception $e) {

        echo $e->getMessage();
    }
}
