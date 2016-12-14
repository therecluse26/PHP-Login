<?php
//Page head constructor
class pageConstruct extends GlobalConf {

    public function buildHead($pagetype = 'page', $title = 'Page') {

        $url = $this->base_url;
        $dir = $this->base_dir;
        $ip = $this->ip_address;

        require_once $dir . "/login/partials/secureheader.php";

        echo $this->htmlhead;
        echo "<title>".$title."</title>";

        require_once $dir . "/login/partials/libincludes.php";  

    }

    //Builds include section of header
    public function pullNav($username = null, $admin = 0, $pagetype = 'page') {

            $url = $this->base_url;
            $dir = $this->base_dir;

       if (!is_null($username)) {

            include $dir . "/login/partials/nav.php";
        } 
    }
}