<?php
    //DATABASE CONNECTION VARIABLES
    $host = ""; // Host name
    $username = ""; // Mysql username
    $password = ""; // Mysql password
    $db_name = ""; // Database name

    $tbl_prefix = ""; //Prefix for all database tables

    $tbl_members = $tbl_prefix."members";
    $tbl_memberinfo = $tbl_prefix."member_info";
    $tbl_admins = $tbl_prefix."admins";
    $tbl_attempts = $tbl_prefix."login_attempts";
    $tbl_deleted = $tbl_prefix."deleted_members";
    $tbl_tokens = $tbl_prefix."tokens";
    $tbl_cookies = $tbl_prefix."cookies";
    $tbl_appConfig = $tbl_prefix."app_config";
