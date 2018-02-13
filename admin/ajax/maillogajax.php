<?php
require_once '../../login/autoload.php';

session_start();

if ((new AuthorizationHandler)->pageOk("superadminpage")) {

    $limit = $_GET['limit'];
    $offset = $_GET['offset'];

    $x = 0;

    $logs = EmailLogger::pullLog($limit, $offset, 0);

        if (!empty($logs)) {

            echo '<table id="userlist" class="table table-sm usertable"><thead class="headrow"><th>Id</th><th>Type</th><th>Status</th><th>Recipient</th><th>Response Message</th><th>Timestamp</th><th><button class="btn btn-info btn-sm pull-right">Select All</button><input type="checkbox" id="selectAll" hidden></input></th></thead><tbody id="mailLogOutput">';

            foreach($logs as $log){
                $x++;

                echo '<tr class="datarow" scope="row" id="row'.$x.'"><td class="col-sm-1">'.$log['id'].'</td><td class="col-sm-1">'.$log['type'].'</td><td class="col-sm-1">'.$log['status'].'</td><td class="col-sm-2">'.$log['recipient'].'</td><td class="col-sm-6">'.$log['response'].'</td><td class="col-sm-2">'.$log['timestamp'].'</td><td><button id="delbutton'.$x.'" class="btn btn-danger btn-sm btn-fixed pull-right" onclick="deleteLog(\''.$log['id'].'\',\''.$x.'\');">Delete</button><input type="checkbox" value="'.$log['id'].'" id="'.$x.'" hidden></input></td></tr>';
            }
            echo '</tbody></table><button id="deleteSelectedLogs" class="btn btn-success" onclick="deleteSelectedLogs();">Delete Selected</button>';
        } else {
            echo '<p class="message">No logs to show</p>';
        };
}
