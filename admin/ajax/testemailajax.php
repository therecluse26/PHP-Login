<?php
/**
* AJAX page for testing email settings in editconfig.php
**/
if ($_GET['t'] == '1') {

    require '../../login/autoload.php';

    $test = new EmailSettings;

    $resp = $test->testMailSettings();

    unset($test);

    if ($resp['status'] == 'false'){



    }

    $jsonresp = json_encode($resp);

    echo $jsonresp;
}
