<?php
if (!isset($_SESSION)) {
    session_start();
}
require '../../login/autoload.php';

$img = profileData::pullUserFields($_SESSION["uid"], array("userimage"));

echo $img["userimage"];
