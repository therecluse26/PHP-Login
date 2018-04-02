<?php

$auth = new AuthorizationHandler;

if (!$auth->pageOk($pagetype)) {
    // Not authorized...

    if ($auth->isLoggedIn()) {
        // User is either logged in as admin but tries to access superadmin page,
        // or logged in as regular user but trying to access admin page.
        // Do not append refurl, then we could get stuuck in a loop...
        header("location:".$this->base_url);
    } else {
        // User not logged in...
        $refurl = urlencode("http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
        session_destroy();
        header("location:".$this->base_url."/login/index.php?refurl=".$refurl);
    }
    exit;
} elseif ($auth->isLoggedIn() && $pagetype == "loginpage") {
    if (array_key_exists("refurl", $_GET)) {

        //Goes to referred url
        $refurl = urldecode($_GET["refurl"]);

        header("location:".$refurl);
    } else {
        header("location:".$this->base_url."/index.php");
    }
}
