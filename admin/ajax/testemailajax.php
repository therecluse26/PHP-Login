<?php
/**
* AJAX page for testing email settings in editconfig.php
**/

require '../../login/autoload.php';

session_start();

if ((new AuthorizationHandler)->pageOk("superadminpage")) {
    
    if ($_GET['t'] == '1') {

        $test = new EmailSettings;

        $resp = $test->testMailSettings();

        unset($test);

        if ($resp['status'] == 'false'){



        }

        $jsonresp = json_encode($resp);

        echo $jsonresp;
    }
}
