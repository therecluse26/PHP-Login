<?php
//DATABASE CONNECTION VARIABLES
$host = "localhost"; // Host name
$username = "root"; // Mysql username
$password = "root"; // Mysql password
$db_name = "login"; // Database name

//DO NOT CHANGE BELOW THIS LINE UNLESS YOU CHANGE THE NAMES OF THE MEMBERS AND LOGINATTEMPTS TABLES

$tbl_prefix = ""; //***PLANNED FEATURE, LEAVE VALUE BLANK FOR NOW*** Prefix for all database tables
$tbl_members = $tbl_prefix."members";
$tbl_memberinfo = $tbl_prefix."memberInfo";
$tbl_admins = $tbl_prefix."admins";
$tbl_attempts = $tbl_prefix."loginAttempts";
$tbl_deleted = $tbl_prefix."deletedMembers";
