<?php
$db_host = $_POST['db_host'];
$db_user = $_POST['db_user'];
$db_pw = $_POST['db_pw'];
$db_name = $_POST['db_name'];
$base_url = $_POST['base_url'];
$sa_user = $_POST['sa_user'];

try {
    $dbconf = file_get_contents('../sql/dbconftemp.txt');

    if (preg_match_all("/{{(.*?)}}/", $dbconf, $m)) {
        foreach ($m[1] as $i => $varname) {
            $dbconf = str_replace($m[0][$i], sprintf('%s', '\''.$_POST[$varname].'\''), $dbconf);
        }
    }

    echo '<b>2) Copy/Paste this database configuration into the `dbconf.php` file located in the /login folder</b>
         <textarea id="dbConfResults" class="form-control" rows="6" cols="70">'. $dbconf .'</textarea>
        <button id="copyDbConf" class="btn btn-default col-md-4">Copy to Clipboard</button><span class="col-md-4" id="copyNotifDbConf"></span>
        <br><br>
        <b>
          3) Pull required depenencies by opening a terminal to the { site_root } directory (the base directory of where this is installed. For example /var/www/html/PHP-Login)
            and running the following command:

            <br><br><em> <strong>composer install --no-dev</strong>

            <br><br>
            <small>* If issues arise, go to <a href="https://getcomposer.org/">https://getcomposer.org/</a> for more comprehensive documentation on this tool.</small>
        </b>
        <br><br>
        <b>
          4) Login as '.$sa_user.' user at <a href="'.$base_url.'/login/index.php">'.$base_url.'/login/index.php</a>
        </b>
        <br><br>
        <b>
          5) Complete site configuration, verify proper functionality then delete the <br>
          <em><strong>{ site_root }/install</strong></em> folder </a>
        </b>
        </li>
        <script>
        $("#copyDbConf").click(function(){

            $("#dbConfResults").select();
            document.execCommand("copy");

            $("#copyNotifDbConf").html("Copied text to clipboard</div>").fadeOut(2000);

        });
        </script>
    </ol>';
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}
