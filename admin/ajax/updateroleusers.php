<?php
require '../../login/autoload.php';

try {
    session_start();

    $request = new CSRFHandler;
    $auth = new AuthorizationHandler;
    $rolehandler = new RoleHandler;

    if ($request->valid_token() && $auth->isAdmin()) {
        try {
            $users = json_decode($_POST['formData'], true);
            //error_log(print_r($users, true));
            $role_id = $_POST['roleId'];

            if (array_key_exists('0', $users[0])) {
                $rolehandler->updateRoleUsers($users, $role_id);
            } else {
                echo 'false';
                return;
            }

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
