<?php
    /*** MOVE THIS TO /login FOLDER AFTER EDITING ***/
    //DATABASE CONNECTION VARIABLES
    $host = "localhost"; // Host name
    $username = "db_user"; // Mysql username
    $password = "db_password"; // Mysql password
    $db_name = "login_db_name"; // Database name

    $tbl_prefix = ""; //Prefix for all database tables
    /*** DO NOT EDIT BELOW THIS LINE ***/
    $tbl_members = $tbl_prefix."members";
    $tbl_memberinfo = $tbl_prefix."member_info";
    $tbl_admins = $tbl_prefix."admins";
    $tbl_attempts = $tbl_prefix."login_attempts";
    $tbl_deleted = $tbl_prefix."deleted_members";
    $tbl_tokens = $tbl_prefix."tokens";
    $tbl_cookies = $tbl_prefix."cookies";
    $tbl_appConfig = $tbl_prefix."app_config";
    $tbl_mailLog = $tbl_prefix."mail_log";