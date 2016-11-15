<?php
function composerInstall() {

    $failure = 0;

    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {

    $compinst = shell_exec('composer install -d ../ 2>&1');

    if(strpos($compinst, "'composer' is not recognized") !== false ){
        $failure = 1;
        $status = "Composer is not installed. Please install the approprate version from <a href='https://getcomposer.org/download/'>https://getcomposer.org/download/</a>, restart your server and try again.";
        }

    } else {
        $compname = "composer";
        $compdown = shell_exec('curl -sS http://getcomposer.org/installer | php -- --filename='.$compname.' 2>&1');

        echo $compdown . "<br><br>";

        $compinst = shell_exec('./'.$compname.' install -d ../ 2>&1');

        $status = nl2br($compdown) . nl2br($compinst);
    }
    $returnArray = array("status" => $status, "failure" => $failure);
    return $returnArray;
    
}
