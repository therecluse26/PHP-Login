<?php
require '../../vendor/autoload.php';

try {
    session_start();

    $permission_db = new PHPLogin\PermissionHandler;
    $request = new PHPLogin\CSRFHandler;
    $auth = new PHPLogin\AuthorizationHandler;

    if ($request->valid_token() && ($auth->isSuperAdmin())) {
        $permission_id = $_POST['permission_id'];

        $permission_roles = $permission_db->listPermissionRoles($permission_id);
        $users = $permission_db->listAllRoles();

        $combo_array = [];
        $combo_array['all_roles'] = $combo_array['permission_roles'] = $combo_array['diff_roles'] = [];

        foreach ($users as $uid => $user) {
            array_push($combo_array['all_roles'], array( 'id'=>$user['id'], 'name'=>$user['name'] ));
            array_push($combo_array['diff_roles'], array( 'id'=>$user['id'], 'name'=>$user['name'] ));
        }

        foreach ($permission_roles as $ruid => $permission_role) {
            array_push($combo_array['permission_roles'], array( 'id'=>$permission_role['id'], 'name'=>$permission_role['name']));
        }

        foreach ($combo_array['permission_roles'] as $permission_role) {
            if (($roleKey = array_search($permission_role['name'], array_column($combo_array['all_roles'], 'name'))) !== false) {
                unset($combo_array['diff_roles'][$roleKey]);
            }
        }

        $combo_array['diff_roles'] = array_values($combo_array['diff_roles']);

        echo json_encode($combo_array);
    } else {
        http_response_code(401);
        throw new Exception("Unauthorized");
    }
} catch (Exception $e) {
    error_log($e->getMessage());
    echo json_encode($e->getMessage());
}
