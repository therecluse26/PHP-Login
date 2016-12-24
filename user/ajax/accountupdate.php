<?php
if (!isset($_SESSION)) {
    session_start();
}
require '../../login/autoload.php';

$config = new AppConfig;
$conf = $config->pullMultiSettings(array("password_policy_enforce", "password_min_length"));

$uid = $_SESSION['uid'];
$resp = array();
$oldpw = UserData::pullUserPassword($uid);

try {

    if (!empty($_POST)) {

        if (((trim($_POST['password1']) == '' || trim($_POST['password1']) == ''))) {
                unset($_POST['password1']);
                unset($_POST['password2']);
        }

        if (array_key_exists('password1', $_POST) && array_key_exists('password2', $_POST)) {


                $pwvalid = PasswordPolicy::validate($_POST['password1'], $_POST['password2'], $conf["password_policy_enforce"], $conf["password_min_length"]);


            if ($pwvalid['status'] == true) {

                    $_POST['password'] = PasswordCrypt::encryptPw($_POST['password1']);

                    unset($_POST['password1']);
                    unset($_POST['password2']);

            } else {

                    unset($_POST['password1']);
                    unset($_POST['password2']);
                    throw new Exception($pwvalid['message']);
            }

        }

        if (array_key_exists('email', $_POST)) {

                $tryemail = $_POST['email'];

            if (!filter_var($tryemail, FILTER_VALIDATE_EMAIL) == true) {
                    throw new Exception("<div class=\"alert alert-danger alert-dismissable\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>Must provide a valid email address</div><div id=\"returnVal\" style=\"display:none;\">false</div>");

            } else {
                    //CHECK DATABASE FOR EXISTING EMAIL
                    $emailtaken = UserData::pullUserByEmail($tryemail);

                if ($emailtaken['email'] == $tryemail) {
                        //EMAIL EXISTS
                        throw new Exception("<div class=\"alert alert-danger alert-dismissable\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>Email already exists</div>");

                } else {

                        //INSERT WITH EMAIL
                        $upsert = UserData::upsertAccountInfo($uid, $_POST);
                }

            }

        } elseif (array_key_exists('password', $_POST)) {

                //INSERT WITHOUT EMAIL
                $upsert = UserData::upsertAccountInfo($uid, $_POST);

        }

            $upsert = UserData::upsertAccountInfo($uid, $_POST);

        if ($upsert == 1) {

            //SUCCESS
            $resp['status'] = true;
            $resp['message'] = "<div class=\"alert alert-success alert-dismissable\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>Changes saved!</div>";
            echo json_encode($resp);

        } else {

            throw new Exception("<div class=\"alert alert-danger alert-dismissable\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>No changes saved</div>");
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
