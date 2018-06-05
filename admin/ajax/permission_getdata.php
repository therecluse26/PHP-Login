<?php
require '../../vendor/autoload.php';

try {
    session_start();

    $permission = new PHPLogin\PermissionHandler;
    $request = new PHPLogin\CSRFHandler;
    $auth = new PHPLogin\AuthorizationHandler;

    if ($request->valid_token() && ($auth->isSuperAdmin()) || $auth->hasPermission('Create Permissions')) {
        $permission_id = $_POST['permission_id'];

        $permission_data = $permission->getPermissionData($permission_id);

        echo json_encode($permission_data);
    } else {
        http_response_code(401);
        throw new Exception("Unauthorized");
    }
} catch (Exception $e) {
    error_log($e->getMessage());
    echo json_encode($e->getMessage());
}
