<?php
//Page head constructor
class pageConstruct extends GlobalConf {

    public function buildHead($pagetype = 'page', $title = 'Page') {

        $url = $this->base_url;
        $dir = $this->base_dir;
        $ip = $this->ip_address;

        include_once $dir . "/login/loginheader.php";

        echo '<!DOCTYPE html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>'.$title.'</title>';

        include_once $dir . "/login/partials/jsinclude.php";  

    }

    //Builds include section of header
    public function buildInc($username = null, $admin = 0, $pagetype = 'page') {

            $url = $this->base_url;
            $dir = $this->base_dir;

            echo '<link href="'.$url.'/login/vendor/components/bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen"><link href="'.$url.'/login/css/main.css" rel="stylesheet" media="screen">';
            

       if (!is_null($username)) {

            include $dir . "/login/partials/nav.php";

            echo '</head><body>';


        } else {
            
            echo '</head><body>';

        }

    }
    
}