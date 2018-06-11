<?php
try {
    require '../../vendor/autoload.php';

    session_start();

    $request = new PHPLogin\CSRFHandler;
    $auth = new PHPLogin\AuthorizationHandler;
    $admin = new PHPLogin\AdminFunctions;

    if ($request->valid_token() && ($auth->isSuperAdmin() || $auth->hasPermission('View Permissions'))) {
        unset($_GET['csrf_token']);
        $columns = array(
            array( 'db' => 'id', 'dt' => 0 ),
            array( 'db' => 'name', 'dt' => 1 ),
            array( 'db' => 'description', 'dt' => 2 ),
            array( 'db' => 'category', 'dt' => 3 ),
            array( 'db' => 'roles', 'dt' => 4 ),
            array( 'db' => 'edit', 'dt' => 5 )
        );

        $data = $admin->getAllPermissions($_GET, $columns);

        echo json_encode($data);
    } else {
        http_response_code(401);
        throw new Exception('Unauthorized');
    }
} catch (Exception $e) {
    error_log($e->getMessage());
    echo json_encode($e->getMessage());
}
