<?php
require '../../vendor/autoload.php';
try {
    //Pull username, generate new ID and hash password
    $newid = uniqid(rand(), false);
    $newuser = str_replace(' ', '', $_POST['newuser']);

    if ($newuser == '') {
        throw new Exception('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Must enter a username</div><div id="returnVal" style="display:none;">false</div>');
    }

    $newemail = $_POST['email'];
    $pw1 = $_POST['password1'];
    $pw2 = $_POST['password2'];
    $userarr = array(array('id'=>$newid, 'username'=>$newuser, 'email'=>$newemail, 'pw'=>$pw1));

    $config = PHPLogin\AppConfig::pullMultiSettings(array("password_policy_enforce", "password_min_length", "signup_thanks", "base_url" ));

    $pwresp = PHPLogin\PasswordHandler::validatePolicy($pw1, $pw2, (bool) $config["password_policy_enforce"], (int) $config["password_min_length"]);

    if (!filter_var($newemail, FILTER_VALIDATE_EMAIL) == true) {
        echo '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Must provide a valid email address</div><div id="returnVal" style="display:none;">false</div>';
    } else {
        //Validation passed
        if (isset($_POST['newuser']) && !empty(str_replace(' ', '', $_POST['newuser'])) && $pwresp['status'] == 1) {
            $a = new PHPLogin\UserHandler;

            $response = $a->createUser($userarr);

            //Success
            if ($response == 1) {
                echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'. $config['signup_thanks'] .'</div><div id="returnVal" style="display:none;">true</div><form action="'.$config['base_url'].'/login/index.php"><button class="btn btn-success">Login</button></form><div id="returnVal" style="display:none;">true</div>';

                try { //Send verification email
                    $m = new PHPLogin\MailHandler;

                    $m->sendMail($userarr, 'Verify');
                } catch (Exception $e) {
                    echo $e->getMessage();
                }
            } else {
                //DB Failure
                PHPLogin\MiscFunctions::mySqlErrors($response);
            }
        } else {
            //Password Failure
            echo $pwresp['message'];
        }
    }
} catch (Exception $x) {
    echo $x->getMessage();
}
