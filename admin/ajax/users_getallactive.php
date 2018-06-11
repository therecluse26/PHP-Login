<?php
try {
    require '../../vendor/autoload.php';

    session_start();

    $request = new PHPLogin\CSRFHandler;
    $auth = new PHPLogin\AuthorizationHandler;
    $admin = new PHPLogin\AdminFunctions;

    if ($request->valid_token() && ($auth->isSuperAdmin() || $auth->hasPermission('View Users'))) {
        unset($_GET['csrf_token']);
        $columns = array(
            array( 'db' => 'id', 'dt' => 0 ),
            array( 'db' => 'username', 'dt' => 1 ),
            array( 'db' => 'email', 'dt' => 2 ),
            array( 'db' => 'roles', 'dt' => 3 ),
            array( 'db' => 'timestamp', 'dt' => 4 )
        );

        $data = $admin->getActiveUsers($_GET, $columns);

        echo json_encode($data);
    } else {
        http_response_code(401);
        throw new Exception('Unauthorized');
    }
} catch (Exception $e) {
    error_log($e->getMessage());
    echo json_encode($e->getMessage());
}
