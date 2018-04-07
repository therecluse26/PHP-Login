<?php
require '../../login/autoload.php';

try {
    session_start();

    $role_db = new RoleHandler;
    $request = new CSRFHandler;
    $auth = new AuthorizationHandler;

    if ($request->valid_token() && $auth->isAdmin()) {
        $user_id = $_POST['user_id'];
        $user_data = ProfileData::pullAllUserInfo($user_id);

        echo json_encode($user_data);
    } else {
        http_response_code(401);
        throw new Exception("Unauthorized");
    }
} catch (Exception $e) {
    error_log($e->getMessage());
    echo json_encode($e->getMessage());
}
