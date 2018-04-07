<?php
try {
    require '../../login/autoload.php';

    session_start();

    $request = new CSRFHandler;
    $auth = new AuthorizationHandler;

    if ($request->valid_token() && $auth->isAdmin()) {
        unset($_GET['csrf_token']);
        $columns = array(
            array( 'db' => 'id', 'dt' => 0 ),
            array( 'db' => 'username', 'dt' => 1 ),
            array( 'db' => 'email', 'dt' => 2 ),
            array( 'db' => 'timestamp', 'dt' => 3 )
        );

        $data = AdminFunctions::getUnverifiedUsers($_GET, $columns);

        echo json_encode($data);
    } else {
        http_response_code(401);
        throw new Exception('Unauthorized');
    }
} catch (Exception $e) {
    error_log($e->getMessage());
    echo json_encode($e->getMessage());
}
