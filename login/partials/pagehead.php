<?php
/**
* Require this at the top of every page
This is the master page that includes
all other controls and classes
**/
if (!isset($_SESSION)) {

    session_start();
}
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
$conf->buildHead($pagetype, $title);

//Checks for cookie
if (isset($_COOKIE['usertoken'])) {
    include_once($conf->base_dir.'/login/ajax/cookiedecode.php');
}

//Pulls navbar
if (array_key_exists('username', $_SESSION)) {

    $conf->pullNav($_SESSION['username'], $_SESSION['admin'], $pagetype);

} else {
    $conf->pullNav(null, 0, $pagetype);
}
