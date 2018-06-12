<?php
/**
* Installs
**/
$status = '';
$failure = false;

if (file_exists("../composer.lock")) {
    unlink("../composer.lock");
}

try {
    if ($f = popen("curl --remote-name https://getcomposer.org/composer.phar 2>&1", "r")) {
        while (!feof($f)) {
            $status = fread($f, 1024);
            $arr_content = array("percent"=> 95, "message" => "Downloading composer.phar... <br>". $status, "failure" => 0);
            file_put_contents("tmp/" . session_id() . ".txt", json_encode($arr_content));
            flush(); // flush buffer
        }
        fclose($f);
        unset($f);
    }

    //Windows
    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
        if ($f = popen("bin\\php.exe composer.phar --no-ansi install --no-dev -d ..\\ 2>&1", "r")) {
            while (!feof($f)) {
                $status = fread($f, 1024);
                $arr_content = array("percent"=> 95, "message" => $status, "failure" => 0);
                file_put_contents("tmp/" . session_id() . ".txt", json_encode($arr_content));

                flush(); // flush buffer
            }
            fclose($f);
            unset($f);
        }

        //Non-windows
    } else {
        $php_bin = shell_exec("which php 2>&1");

        $cwd = getcwd();

        putenv("COMPOSER_HOME=$cwd");

        if ($f = popen($php_bin . " ./composer.phar --no-ansi install --no-dev -d ../ 2>&1", "r")) {
            while (!feof($f)) {
                $status = fread($f, 1024);
                $arr_content = array("percent"=> 95, "message" => "Pulling dependencies... <br>". $status, "failure" => 0);
                file_put_contents("tmp/" . session_id() . ".txt", json_encode($arr_content));

                flush(); // flush buffer
            }
            fclose($f);
            unset($f);
        }
    }
} catch (Exception $e) {
    $arr_content = array("percent"=> 95, "message" => $e->getMessage(), "failure" => 1);
    file_put_contents("tmp/" . session_id() . ".txt", json_encode($arr_content));
    flush();
}

$i++;
