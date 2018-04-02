<?php
require_once '../../login/autoload.php';

$columns = array(
    array( 'db' => 'id', 'dt' => 0 ),
    array( 'db' => 'username', 'dt' => 1 ),
    array( 'db' => 'email', 'dt' => 2 ),
    array( 'db' => 'roles', 'dt' => 3 ),
    array( 'db' => 'timestamp', 'dt' => 4 )
);

$data = UserHandler::getActiveUsers($_GET, $columns);

echo json_encode($data);
