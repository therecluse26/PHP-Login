<?php
/***
Require this at the top of every page
***/
if (!isset($_SESSION)) {

    session_start();
}
//Recursively searches for autoload.php, enabling user to include this header file in subdirectories up to 20 directories deep
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

$conf = new GlobalConf;
define("BASE_URL", $conf->base_url);

$pageHead = new pageConstruct;
$pageHead->buildHead($pagetype, $title);

//Checks for cookie
if (isset($_COOKIE['usertoken'])) {
    include_once($conf->base_dir.'/login/ajax/cookiedecode.php');
}

//Pulls nav dropdown
if (array_key_exists('username', $_SESSION)) {

    $pageHead->pullNav($_SESSION['username'], $_SESSION['admin'], $pagetype);

} else {

    $pageHead->pullNav(null, 0, $pagetype);
}
