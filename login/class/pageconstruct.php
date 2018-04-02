<?php
/**
* Builds the site skeleton, handles redirects and security
**/
class PageConstruct extends AppConfig
{
    /**
    * IP address
    * @var string
    */
    public static $ip;
    /**
    * `$this->htmlhead` pulls begging part of `<head>` section of page from `app_config` table.
    * `secureheader.php` handles redirects and security.
    * `libincludes.php` handles required js and css libraries for login script
    **/
    public function buildHead($pagetype = 'page', $title = 'Page')
    {
        $ip = $_SERVER["REMOTE_ADDR"];

        require_once $this->base_dir . "/login/misc/secureheader.php";

        echo $this->htmlhead;
        echo "<title>".$title."</title>";

        require_once $this->base_dir . "/login/partials/libincludes.php";
    }
    /**
    * Builds page navbar
    **/
    public function pullNav($username = null, $admin = 0, $pagetype = 'page', $barmenu = null)
    {
        $url = $this->base_url;
        $mainlogo = $this->mainlogo;

        include $this->base_dir . "/login/partials/nav.php";
    }
}
