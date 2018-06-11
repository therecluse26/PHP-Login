<?php
try {
    require '../../vendor/autoload.php';

    session_start();

    $request = new PHPLogin\CSRFHandler;
    $auth = new PHPLogin\AuthorizationHandler;

    if ($request->valid_token() && ($auth->isSuperAdmin() || $auth->hasPermission('Create Permissions'))) {
        unset($_POST['csrf_token']);

        $permissionName = $_POST['permissionName'];
        $permissionDescription = $_POST['permissionDescription'];
        $permissionCategory = $_POST['permissionCategory'];

        $permission = new PHPLogin\PermissionHandler;

        $resp = $permission->createPermission($permissionName, $permissionDescription, $permissionCategory);

        echo json_encode($resp);
    } else {
        http_response_code(401);
        throw new Exception('Unauthorized');
    }
} catch (Exception $e) {
    error_log($e->getMessage());
    echo json_encode($e->getMessage());
}
