<?php
session_start();

require "installscript.php";
require "instcomposer.php";

$resp = '';
$failure = 0;
$total = 15;
$i = 0;
$status = '';

$dbhost = $_POST['dbhost'];
$dbuser = $_POST['dbuser'];
$dbpw = $_POST['dbpw'];
$dbname = $_POST['dbname'];
$tblprefix = $_POST['tblprefix'];
$superadmin = $_POST['superadmin'];
$saemail = $_POST['saemail'];
$said = $_POST['said'];
$sapw = $_POST['sapw'];

$arr_content = array();

while ($i < $total) {

    if ($failure == 1) {
        break;
    }
    else {
        if($i < 13) {
            $statobj = installDb($i, $dbhost, $dbname, $dbuser, $dbpw, $tblprefix, $superadmin, $saemail, $said, $sapw);
        }
        else if ($i == 13) {
            $statobj = composerInstall();
            sleep(1);
        }


        $percent = intval(($i+1)/$total * 100);

        $arr_content['percent'] = $percent;
        $arr_content['message'] = $statobj['status'];
        $arr_content['failure'] = $statobj['failure'];

        file_put_contents("tmp/" . session_id() . ".txt", json_encode($arr_content));

        $sleep = rand(100000,1000000);
        usleep($sleep);

        unset($conn);
        $i++;
    }
};
