<?php
require "../vendor/autoload.php";
PHPLogin\LoginHandler::logout();

header("location:../index.php");
