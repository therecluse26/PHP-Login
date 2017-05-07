<?php
//CREATES dbconf.php FILE
try {
    $status = "Generating dbconf.php file";
    $dbconf_file = fopen('../login/dbconf.php', 'w') or die('Unable to open file!');
    $dbconf_text = '<?php
    //DATABASE CONNECTION VARIABLES
    $host = "'.$dbhost.'"; // Host name
    $username = "'.$dbuser.'"; // Mysql username
    $password = "'.$dbpw.'"; // Mysql password
    $db_name = "'.$dbname.'"; // Database name

    $tbl_prefix = "'.$tblprefix.'"; //Prefix for all database tables
    $tbl_members = $tbl_prefix."members";
    $tbl_memberinfo = $tbl_prefix."member_info";
    $tbl_admins = $tbl_prefix."admins";
    $tbl_attempts = $tbl_prefix."login_attempts";
    $tbl_deleted = $tbl_prefix."deleted_members";
    $tbl_tokens = $tbl_prefix."tokens";
    $tbl_cookies = $tbl_prefix."cookies";
    $tbl_appConfig = $tbl_prefix."app_config";
    $tbl_mailLog = $tbl_prefix."mail_log";';

    fwrite($dbconf_file, $dbconf_text);
    fclose($dbconf_file);

    $i++;

} catch (Exception $f) {
    echo "Failed to write dbconf.php file" , $f->getMessage(), "\n";
}
