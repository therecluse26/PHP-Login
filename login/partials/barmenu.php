<?php

$anonymous = array(
    "Index" => "index.php",
    "About" => "about.php",
    "External stuff" => array(
        "Google" => "//google.com",
        "Web Root" => "/"
    )
);

$user = array(
    "Page 2" => "page_2.php",
    "Page 3" => "page_3.php"
);

$admin = array(
    "Admin-Page" => "page_4.php"
);

$superadmin = array();

// Define the buttons in the menu bar
// "barmenu" is a keyword which definies the used menu
$barmenu = array(
    "anonymous" => $anonymous,
    "user" => array_merge($anonymous, $user),
    "admin" => array_merge($anonymous, $user, $admin),
    "superadmin" => array_merge($anonymous, $user, $admin, $superadmin)
);
