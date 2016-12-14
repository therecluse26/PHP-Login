<?php
require '../login/partials/pagehead.php';

if (isset($_SESSION)) {

    $uid = $_SESSION['uid'];

    $usr = profileData::pullAllUserInfo($uid);

    foreach($usr as $field){
        echo "<p>$field</p>";
    }

} else {

    header("location:../login/index.php");

}