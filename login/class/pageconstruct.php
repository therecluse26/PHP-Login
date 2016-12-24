<?php
//Page head constructor
class PageConstruct extends AppConfig {

    public function buildHead($pagetype = 'page', $title = 'Page') {

        $ip = $_SERVER["REMOTE_ADDR"];

        require_once $this->base_dir . "/login/partials/secureheader.php";

        echo $this->htmlhead;
        echo "<title>".$title."</title>";

        require_once $this->base_dir . "/login/partials/libincludes.php";

    }

    //Builds include section of header
    public function pullNav($username = null, $admin = 0, $pagetype = 'page') {

            $url = $this->base_url;
            $mainlogo = $this->mainlogo;

       if (!is_null($username)) {

            include $this->base_dir . "/login/partials/nav.php";
        }
    }
}
