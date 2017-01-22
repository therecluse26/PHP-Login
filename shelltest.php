<?php

echo shell_exec('which curl');

echo shell_exec('which php');

echo PHP_OS;
/*

if (shell_exec('which curl')) {
    echo "Curl exists";
} else {
    echo "No curl";
}

if ($cmd2 = shell_exec('which composer')) {
    echo "Composer exists";
} else {
    echo "No composer";
}
*/
