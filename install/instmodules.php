<?php
function composerInstall() {
    trigger_error(shell_exec("composer install"));
    $status = "test";
    $failure = 0;
    $returnArray = array("status" => $status, "failure" => $failure);
    return $returnArray;
}


