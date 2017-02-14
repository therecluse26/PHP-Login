<?php
/**
* AJAX page for saving configuration in editconfig.php
**/
require "../../login/autoload.php";
$conf = new AppConfig;

try {

    $update = $conf->updateMultiSettings($_POST);

    echo json_encode($update);

} catch (Exception $e) {

    echo $e->getMessage();
}


