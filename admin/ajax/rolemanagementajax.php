<?php
try {
    require '../../login/autoload.php';

    session_start();

    $request = new CSRFHandler;
    $auth = new AuthorizationHandler;

    if ($request->valid_token() && $auth->isSuperAdmin()) {
        unset($_GET['csrf_token']);
        $columns = array(
            array( 'db' => 'id', 'dt' => 0 ),
            array( 'db' => 'name', 'dt' => 1 ),
            array( 'db' => 'description', 'dt' => 2 ),
            array( 'db' => 'required', 'dt' => 3 ),
            array( 'db' => 'default_role', 'dt' => 4 ),
            array( 'db' => 'users', 'dt' => 4 )
        );

        $data = AdminFunctions::getAllRoles($_GET, $columns);

        echo json_encode($data);
    } else {
        http_response_code(401);
        throw new Exception('Unauthorized');
    }
} catch (Exception $e) {
    error_log($e->getMessage());
    echo json_encode($e->getMessage());
}
