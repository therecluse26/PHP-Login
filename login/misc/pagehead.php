<?php
/**
* Require this at the top of every page
* This is the master page that includes
* all other controls and classes
**/
try {
    require dirname(dirname(__DIR__))."/vendor/autoload.php";

    if (!isset($userrole)) {
        $userrole = null;

        if (!isset($title)) {
            $title = 'Page';
        }
    }

    /**
    * The $conf object's class extends AppConfig, so PHPLogin\AppConfig variables can be pulled from it
    **/
    $auth = new PHPLogin\AuthorizationHandler;
    $conf = new PHPLogin\PageConstructor($auth);

    if (!isset($_SESSION)) {
        session_start();
        date_default_timezone_set($conf->timezone);
    }

    //Builds page head and returns CSRF token object
    $csrf = $conf->buildHead($userrole, $title);

    //Checks for cookie
    if (isset($_COOKIE['usertoken'])) {
        include_once($conf->base_dir.'/login/ajax/cookiedecode.php');
    }
} catch (Exception $ex) {
    die($ex->getMessage());
}
