<?php
/***
Require this at the top of every page
***/
if (!isset($_SESSION)) {

    session_start();
}

//Recursively searches for autoload.php, enabling user to include this file in subdirectories
$n = 0;
do {
    if (file_exists('login/autoload.php')) {
        include_once('login/autoload.php');
        break;
    } else {
        chdir('../');
    }
    $n++;
} while (!file_exists('/login/autoload.php') && $n < 9);

if (!isset($pagetype)) {

    $pagetype = 'page';

    if (!isset($title)) {

        $title = 'Page';
    }
}

$conf = new GlobalConf;
$base_url = $conf->base_url;

$pageHead = new pageConstruct;
$pageHead->buildHead($pagetype, $title);

if (array_key_exists('username', $_SESSION)) {

    $pageHead->pullNav($_SESSION['username'], $_SESSION['admin'], $pagetype);

} else {

    $pageHead->pullNav(null, 0, $pagetype);
}
