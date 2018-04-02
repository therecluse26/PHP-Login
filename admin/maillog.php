<?php
/**
* Page that allows admins to verify or delete new (unverified) users
**/
$pagetype = 'superadminpage';
$title = 'Mail Log';
require_once '../login/misc/pagehead.php';
$logcount = EmailLogger::pullLogCount();
?>

</head>
<body>
<div id="limithid" hidden>20</div>
<div id="offsethid" hidden>0</div>
<div id="currenthid" hidden></div>
<div id="rowcounthid" hidden><?php echo $logcount['rowcount']; ?></div>

<div class="container-fluid">
<?php

echo '<div class="pull-left"><h3>Mail Log</h3></div>
<div class="pull-right"><button id="leftbtn" type="button" class="btn btn-default" onClick="backBtn();"> <span class="glyphicon glyphicon-arrow-left"></span></button><button id="rightbtn" type="button" class="btn btn-default" onClick="forwardBtn();"> <span class="glyphicon glyphicon-arrow-right"></span></button></div><br><br><div class="pull-right"><span id="pageCount"></span><span id="rowcount">'.$logcount['rowcount'].'</span></div>
';
?>

<div id="mailLogOutput">
</div>

    </table>
    </div>
    </form>
    </body>
</html>
