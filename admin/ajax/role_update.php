<?php
try {
    require '../../vendor/autoload.php';

    session_start();

    $request = new PHPLogin\CSRFHandler;
    $auth = new PHPLogin\AuthorizationHandler;

    if ($request->valid_token() && ($auth->isSuperAdmin() || $auth->hasPermission('Create Roles'))) {
        unset($_POST['csrf_token']);

        $roleId = $_POST['roleId'];
        $roleName = $_POST['roleName'];
        $roleDescription = $_POST['roleDescription'];

        $role = new PHPLogin\RoleHandler;

        $resp = $role->updateRole($roleId, $roleName, $roleDescription);

        echo json_encode($resp);
    } else {
        http_response_code(401);
        throw new Exception('Unauthorized');
    }
} catch (Exception $e) {
    error_log($e->getMessage());
    echo json_encode($e->getMessage());
}
