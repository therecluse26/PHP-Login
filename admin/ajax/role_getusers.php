<?php
require '../../vendor/autoload.php';

try {
    session_start();

    $role_db = new PHPLogin\RoleHandler;
    $request = new PHPLogin\CSRFHandler;
    $auth = new PHPLogin\AuthorizationHandler;

    if ($request->valid_token() && ($auth->isSuperAdmin() || $auth->hasPermission('Assign Users to Roles'))) {
        $role_id = $_POST['role_id'];

        $role_users = $role_db->listRoleUsers($role_id);
        $users = $role_db->listAllActiveUsers();

        $combo_array = [];
        $combo_array['all_users'] = $combo_array['role_users'] = $combo_array['diff_users'] = [];

        foreach ($users as $uid => $user) {
            array_push($combo_array['all_users'], array( 'id'=>$user['id'], 'username'=>$user['username'] ));
            array_push($combo_array['diff_users'], array( 'id'=>$user['id'], 'username'=>$user['username'] ));
        }

        foreach ($role_users as $ruid => $role_user) {
            array_push($combo_array['role_users'], array( 'id'=>$role_user['id'], 'username'=>$role_user['username']));
        }

        foreach ($combo_array['role_users'] as $role_user) {
            if (($roleKey = array_search($role_user['username'], array_column($combo_array['all_users'], 'username'))) !== false) {
                unset($combo_array['diff_users'][$roleKey]);
            }
        }

        $combo_array['diff_users'] = array_values($combo_array['diff_users']);

        echo json_encode($combo_array);
    } else {
        http_response_code(401);
        throw new Exception("Unauthorized");
    }
} catch (Exception $e) {
    error_log($e->getMessage());
    echo json_encode($e->getMessage());
}
