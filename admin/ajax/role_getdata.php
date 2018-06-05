<?php
require '../../vendor/autoload.php';

try {
    session_start();

    $role = new PHPLogin\RoleHandler;
    $request = new PHPLogin\CSRFHandler;
    $auth = new PHPLogin\AuthorizationHandler;

    if ($request->valid_token() && ($auth->isSuperAdmin()) || $auth->hasPermission('Create Roles')) {
        $role_id = $_POST['role_id'];

        $role_data = $role->getRoleData($role_id);

        echo json_encode($role_data);
    } else {
        http_response_code(401);
        throw new Exception("Unauthorized");
    }
} catch (Exception $e) {
    error_log($e->getMessage());
    echo json_encode($e->getMessage());
}
