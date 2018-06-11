<?php
try {
    require '../../vendor/autoload.php';

    session_start();

    $request = new PHPLogin\CSRFHandler;
    $auth = new PHPLogin\AuthorizationHandler;

    if ($request->valid_token() && $auth->isLoggedIn()) {
        $img = PHPLogin\ProfileData::pullUserFields($_SESSION["uid"], array("userimage"));

        echo $img["userimage"];
    } else {
        throw new Exception("Unauthorized");
    }
} catch (Exception $e) {
    echo $e->getMessage();
}
