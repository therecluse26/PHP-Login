<?php
if (isset($_COOKIE["usertoken"])) {
    setcookie("usertoken", "", time() - 10000, "/");
}
session_start();
session_destroy();
header("location:index.php");
