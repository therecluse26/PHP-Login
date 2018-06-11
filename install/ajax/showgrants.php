<?php

$dbhost = $_POST['dbhost'];
$dbuser = $_POST['dbuser'];
$dbpw = $_POST['dbpw'];
$resp = array();
try {
    $conn = new PDO("mysql:host={$dbhost}", $dbuser, $dbpw);
    $conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    $sql = "SHOW GRANTS FOR {$dbuser}";
    $grants = $conn->exec($sql);

    $resp['status'] = 1;
    $resp['data'] = 'blah blah';
    //$resp['data'] = $grants->fetch(\PDO::FETCH_ASSOC);
    return $resp['data'];

} catch (Exception $e) {

    $resp['status'] = 0;
    $resp['data'] = 'blah blah';

    //$resp['data'] = $e->getMessage();

    return $resp['data'];

}
