<?php
//SYSTEM SETTINGS 
$base_url = 'http://' . $_SERVER['SERVER_NAME']; 
$signin_url = substr($base_url . $_SERVER['PHP_SELF'],0, -strlen(basename($_SERVER['PHP_SELF'])));
$invalid_mod = '"$mod_email" is not a valid email address';
?>