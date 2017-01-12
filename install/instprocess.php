<?php
if(!isset($_SESSION)) {
    session_start();
}
require "installscript.php";

set_time_limit(5000);

$resp = '';
$failure = 0;
$total = 21;
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
$base_dir = base64_decode($_POST['base_dir']);
$base_url = base64_decode($_POST['base_url']);
$site_name = $_POST['site_name'];

$arr_content = array();
$settingsArr = array("base_dir"=>$base_dir, "base_url"=>$base_url, "site_name"=>$site_name);

while ($i < $total) {

    if ($failure == 1) {
        break;
    }
    else {
        if($i < 17) {

            $statobj = installDb($i, $dbhost, $dbname, $dbuser, $dbpw, $tblprefix, $superadmin, $saemail, $said, $sapw);

        } elseif ($i == 17) {

            //Insert basic app settings
            $statobj = installDb($i, $dbhost, $dbname, $dbuser, $dbpw, $tblprefix, $superadmin, $saemail, $said, $sapw, $settingsArr);

        } elseif ($i == 18) {

            require "instcomposer.php";

        } else {

            $refurl = urlencode($base_url."/admin/editconfig.php");

            $statobj['status'] = 'Installation Complete!<br>
            <a href="'.$base_url.'/login/index.php?refurl='.$refurl.'">Sign In And Finish Configuration</a>';

            $statobj['failure'] = 0;
        }

        $percent = intval(($i+1)/$total * 100);

        $arr_content['percent'] = $percent;
        $arr_content['message'] = $statobj['status'];
        $arr_content['failure'] = $statobj['failure'];

        file_put_contents("tmp/" . session_id() . ".txt", json_encode($arr_content));

        $sleep = rand(50000,500000);
        usleep($sleep);

        unset($conn);
        $i++;
    }
};
