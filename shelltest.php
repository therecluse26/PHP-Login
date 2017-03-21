<?php
$ostype = php_uname("s");
$osrelease = php_uname("r");

echo "OS: ". $ostype;
echo "<br>Release: ". $osrelease . "<br>";

echo ( shell_exec(".\\install\\bin\\php.exe 'echo \"blah\"'; 2>&1") );
