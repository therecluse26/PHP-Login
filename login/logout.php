<?php
require "autoload.php";
LoginHandler::logout();

header("location:../index.php");
