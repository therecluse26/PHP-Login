<?php
session_start();

if(!isset($page)){
    $page = 'page';
    $title = 'Page';
}

//"login" directory
$up_dir = realpath(__DIR__ . '/..');

if (file_exists($up_dir.'/autoload.php')) {
    include_once($up_dir.'/autoload.php');
} 

$pageHead = new pageConstruct;

$pageHead->buildHead($page);

if(array_key_exists('username', $_SESSION)){
    $pageHead->buildInc($_SESSION['username'], $_SESSION['admin'], $page, $title);
} else {
    $pageHead->buildInc(null, 0, $page, $title);

}


