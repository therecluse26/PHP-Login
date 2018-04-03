<?php
try {
    require '../../login/autoload.php';

    session_start();

    $request = new CSRFHandler;
    $auth = new AuthorizationHandler;

    if ($request->valid_token() && $auth->isLoggedIn()) {
        $img = profileData::pullUserFields($_SESSION["uid"], array("userimage"));

        echo $img["userimage"];
    } else {
        throw new Exception("Unauthorized");
    }
} catch (Exception $e) {
    echo $e->getMessage();
}
