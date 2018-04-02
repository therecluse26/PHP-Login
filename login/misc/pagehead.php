<?php
/**
* Require this at the top of every page
* This is the master page that includes
* all other controls and classes
**/
try {
    /**
    * Recursively searches for autoload.php, enabling user to include this header file in subdirectories up to 20 directories deep
    **/
    $n = 0;
    do {
        if (file_exists('login/autoload.php')) {
            include_once('login/autoload.php');
            break;
        } else {
            chdir('../');
        }
        $n++;
    } while (!file_exists('/login/autoload.php') && $n < 20);

    if (!isset($pagetype)) {
        $pagetype = 'page';

        if (!isset($title)) {
            $title = 'Page';
        }
    }

    /**
    * This object's class extends AppConfig, so AppConfig variables can be pulled from it
    **/
    $conf = new PageConstruct;

    if (!isset($_SESSION)) {
        session_start();

        date_default_timezone_set($conf->timezone);
    }

    $conf->buildHead($pagetype, $title);

    //Checks for cookie
    if (isset($_COOKIE['usertoken'])) {
        include_once($conf->base_dir.'/login/ajax/cookiedecode.php');
    }

    //Pulls navbar
    if (array_key_exists('username', $_SESSION)) {
        $conf->pullNav($_SESSION['username'], $_SESSION['admin'], $pagetype);

        //Checks for proper mailer configuration. Only checks connection if email_working db entry is false
        if ($conf->email_working == 'false') {
            $mailtest = new EmailSettings;

            $mailresult = $mailtest->testMailSettings();

            if ($mailresult['status'] == 'true') {
                $conf->updateMultiSettings(array("email_working"=>"true"));
            } else {
                echo "<div class=\"alert alert-danger alert-dismissable\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>Server Error: Please contact site administrator at <a href='mailto:".$conf->admin_email."?subject=Login%20System%20Error&body=Error%20Code%20-%20xER41300'>".$conf->admin_email."</a> and relay this error - xER41300 </div>";
            }
        }
    } else {
        $conf->pullNav(null, 0, $pagetype);
    }
} catch (Exception $ex) {
    die($ex->getMessage());
}
