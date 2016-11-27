<?php
if(!isset($_SESSION)) 
{ 
    session_start(); 
} 

if(!isset($pagetype)){
    $pagetype = 'page';
    $title = 'Page';
}

//"login" directory
$up_dir = realpath(__DIR__ . '/..');

if (file_exists($up_dir.'/autoload.php')) {
    include_once($up_dir.'/autoload.php');
} 

$pageHead = new pageConstruct;

$pageHead->buildHead($pagetype, $title);

if(array_key_exists('username', $_SESSION)){
    $pageHead->buildInc($_SESSION['username'], $_SESSION['admin'], $pagetype);
} else {
    $pageHead->buildInc(null, 0, $pagetype);

}


