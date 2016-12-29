<?php
/**
* Installs
**/
$status = '';
$failure = false;

try {
    if( ($f = popen("curl --remote-name https://getcomposer.org/composer.phar 2>&1", "r")) ) {
        while( !feof($f) ){

            $status = fread($f, 1024);
            $arr_content = array("percent"=> 95, "message" => "Downloading composer.phar... <br>". $status, "failure" => 0);
            file_put_contents("tmp/" . session_id() . ".txt", json_encode($arr_content));
            flush(); // you have to flush buffer
        }
        fclose($f);
    }
} catch (Exception $e) {

    $arr_content = array("percent"=> 95, "message" => $e->getMessage(), "failure" => 1);
    file_put_contents("tmp/" . session_id() . ".txt", json_encode($arr_content));
    flush();
}

try {

    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {

        if( ($f = popen("php composer.phar install -d ../ 2>&1", "r")) ) {
            while( !feof($f) ){
                $status = fread($f, 1024);
                $arr_content = array("percent"=> 95, "message" => "Pulling dependencies... <br>". $status, "failure" => 0);
                file_put_contents("tmp/" . session_id() . ".txt", json_encode($arr_content));
                flush(); // you have to flush buffer
            }
            fclose($f);
        }
    }

} catch (Exception $e) {

    $arr_content = array("percent"=> 95, "message" => $e->getMessage(), "failure" => 1);
    file_put_contents("tmp/" . session_id() . ".txt", json_encode($arr_content));
    flush(); // you have to flush buffer
}

try {
    if( ($f = popen("php composer.phar install -d ../ 2>&1", "r")) ) {

        while( !feof($f) ){

            $status = fread($f, 1024);

            $arr_content = array("percent"=> 95, "message" => "Pulling dependencies... <br>". $status, "failure" => 0);

            file_put_contents("tmp/" . session_id() . ".txt", json_encode($arr_content));

            flush(); // you have to flush buffer

        }
        fclose($f);
    }
} catch (Exception $e) {

    $arr_content = array("percent"=> 95, "message" => $e->getMessage(), "failure" => 1);
    file_put_contents("tmp/" . session_id() . ".txt", json_encode($arr_content));
    flush(); // you have to flush buffer

}
$i++;
