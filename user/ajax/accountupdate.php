<?php
if(!isset($_SESSION))
{
    session_start();
}
require '../../login/autoload.php';

$conf = new GlobalConf;
$uid = $_SESSION['uid'];
$tryemail = $_POST['email'];
$resp = array();

try{

    if(!empty($_POST)){

        if(array_key_exists('email', $_POST)){

            $emailtaken = UserData::pullUserByEmail($tryemail);

            if($emailtaken['email'] == $tryemail) {

                throw new Exception("<div class=\"alert alert-danger alert-dismissable\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>Email already exists</div>");

            } else {

                $upsert = UserData::upsertAccountInfo($uid, $_POST);

            }

        } else {

                $upsert = UserData::upsertAccountInfo($uid, $_POST);

        }

        $upsert = UserData::upsertAccountInfo($uid, $_POST);

        if($upsert == 1) {

            $resp['status'] = true;
            $resp['message'] = "<div class=\"alert alert-success alert-dismissable\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>Changes saved!</div>";

            echo json_encode($resp);

        } else {
            throw new Exception("<div class=\"alert alert-danger alert-dismissable\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>Database update failed</div>");
        }

    } else {

        $resp['status'] = false;
        $resp['message'] = "<div class=\"alert alert-success alert-dismissable\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>No changes</div>";
        echo json_encode($resp);

    }

} catch (Exception $e) {

    $resp['status'] = false;
    $resp['message'] = $e->getMessage();

    echo json_encode($resp);

}
