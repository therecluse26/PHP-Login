<?php
//PUT THIS HEADER ON TOP OF EACH UNIQUE PAGE
if (($pagetype != 'loginpage') && !array_key_exists('username', $_SESSION) || (array_key_exists('username', $_SESSION)  && $ip != getenv ("REMOTE_ADDR")))
 {
    header("location:".$url."/login/index.php");
    exit;
} else if (array_key_exists('username', $_SESSION) && $pagetype == 'loginpage') {
    header("location:".$url."/index.php");
}
