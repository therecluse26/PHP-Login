<?php
$ostype = php_uname("s");
$osrelease = php_uname("r");

echo "OS: ". $ostype;
echo "<br>Release: ". $osrelease;
