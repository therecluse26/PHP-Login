<?php
require '../../vendor/autoload.php';

try {
    session_start();

    $request = new PHPLogin\CSRFHandler;
    $auth = new PHPLogin\AuthorizationHandler;
    $rolehandler = new PHPLogin\RoleHandler;

    if ($request->valid_token() && ($auth->isSuperAdmin() || $auth->hasPermission('Assign Users to Roles'))) {
        try {
            $users = json_decode($_POST['formData'], true);

            $role_id = $_POST['roleId'];

            $rolehandler->updateRoleUsers($users, $role_id);

            http_response_code(200);
            echo "true";
            return;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    } else {
        http_response_code(401);
        throw new Exception("Unauthorized");
    }
} catch (Exception $e) {
    error_log($e->getMessage());
    echo json_encode($e->getMessage());
}
