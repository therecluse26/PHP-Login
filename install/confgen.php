<?php
//CREATES dbconf.php FILE
try {
    $status = "Generating dbconf.php file";
    $dbconf_file = fopen('../login/dbconf.php', 'w') or die('Unable to open file!');
    $dbconf_text = <<<EOD
<?php
//DATABASE CONNECTION VARIABLES
\$host = '{$dbhost}'; // Host name
\$username = '{$dbuser}'; // Mysql username
\$password = '{$dbpw}'; // Mysql password
\$db_name = '{$dbname}'; // Database name

\$tbl_prefix = '{$tblprefix}'; //Prefix for all database tables
\$tbl_members = \$tbl_prefix.'members';
\$tbl_memberinfo = \$tbl_prefix.'member_info';
\$tbl_roles = \$tbl_prefix.'roles';
\$tbl_member_roles = \$tbl_prefix.'member_roles';
\$tbl_attempts = \$tbl_prefix.'login_attempts';
\$tbl_deleted = \$tbl_prefix.'deleted_members';
\$tbl_tokens = \$tbl_prefix.'tokens';
\$tbl_cookies = \$tbl_prefix.'cookies';
\$tbl_app_config = \$tbl_prefix.'app_config';
\$tbl_mail_log = \$tbl_prefix.'mail_log';
\$tbl_member_jail = \$tbl_prefix.'member_jail';
\$tbl_permissions = \$tbl_prefix.'permissions';
\$tbl_role_permissions = \$tbl_prefix.'role_permissions';
EOD;

    fwrite($dbconf_file, $dbconf_text);
    fclose($dbconf_file);

    $i++;
} catch (Exception $f) {
    echo "Failed to write dbconf.php file" , $f->getMessage(), "\n";
}
