<?php

$tbl_prefix = 'TESTING___';
$db_host = '0.0.0.0';
$db_name = 'php_loggin';
$db_user = 'root';
$db_pass = 'root';
$db_port = 8889;


try {
    $conn = new \PDO('mysql:host='.$db_host.';charset=utf8', $db_user, $db_pass);
    $conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

    $stmt = $conn->prepare("CREATE SCHEMA $db_name;");
    $stmt->execute();


    // Fills config template with variables
    $config_source = file_get_contents('source/phinx_source.yml');
    $config_update = str_replace(['{$dbhost}', '{$dbname}', '{$dbuser}', '{$dbpass}', '{$dbport}'], [$db_host, $db_name, $db_user, $db_pass, $db_port], $config_source);
    // Generates new config file
    file_put_contents('phinx.yml', $config_update);

    echo "Config generated successfully <br>";

    // Generates new phinx database migration files from install/db/source/migrations directory
    foreach (new \DirectoryIterator(realpath('source/migrations')) as $fileInfo) {
        if ($fileInfo->isDot()) {
            continue;
        }
        $migration_source = file_get_contents($fileInfo->getPathname());
        $migration_update = str_replace('{$tblprefix}', $tbl_prefix, $migration_source);
        $new_migration = substr('migrations/'.$fileInfo->getFilename(), 0, -1);
        file_put_contents($new_migration, $migration_update);

        echo substr($fileInfo->getFilename(), 0, -1) . " file created <br>";
    }
} catch (\PDOException $e) {
    var_dump($e);
} catch (\Exception $e) {
    echo $e->getMessage();
}
