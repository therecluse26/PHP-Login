<?php
if(!isset($_SESSION)) 
{ 
    session_start(); 
} 
require '../../login/autoload.php';

$img = profileData::pullUserFields($_SESSION["uid"], Array("userimage"));

echo $img["userimage"];