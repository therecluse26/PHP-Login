<?php
require '../../vendor/autoload.php';

try {
    session_start();

    $role_db = new PHPLogin\RoleHandler;
    $request = new PHPLogin\CSRFHandler;
    $auth = new PHPLogin\AuthorizationHandler;

    if ($request->valid_token() && ($auth->isSuperAdmin() || $auth->hasPermission('View Users'))) {
        $user_id = $_POST['user_id'];
        $user_data = PHPLogin\ProfileData::pullAllUserInfo($user_id);

        echo json_encode($user_data);
    } else {
        http_response_code(401);
        throw new Exception("Unauthorized");
    }
} catch (Exception $e) {
    error_log($e->getMessage());
    echo json_encode($e->getMessage());
}
