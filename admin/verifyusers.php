<?php
/**
* Page that allows admins to verify or delete new (unverified) users
**/
$pagetype = 'adminpage';
$title = 'Admin Verification';
require '../login/misc/pagehead.php';

$users = UserData::userVerifyList();
$x = 0;
?>
</head>
<body>
<div class="container-fluid">
<?php
echo "<h3>Verify/Delete Users</h3>";

    if (!empty($users)) {

        echo '<table id="userlist" class="table table-sm"><thead class="headrow"><th>Username</th><th>Email</th><th>Timestamp</th><th><button class="btn btn-info btn-sm pull-right">Select All</button><input type="checkbox" id="selectAll" hidden></input></th></thead>';

        foreach($users as $user){
            $x++;
            echo '<tr class="datarow" scope="row" id="row'.$x.'"><td>'.$user['username'].'</td><td>'.$user['email'].'</td><td>'.$user['timestamp'].'</td><td><button id="verbutton'.$x.'" class="btn btn-success btn-sm btn-fixed pull-right" onclick="verifyUser(\''.$user['id'].'\',\''.$user['email'].'\',\''.$user['username'].'\',\''.$x.'\');">Verify</button><button id="delbutton'.$x.'" class="btn btn-danger btn-sm btn-fixed pull-right" onclick="deleteUser(\''.$user['id'].'\',\''.$user['email'].'\',\''.$user['username'].'\',\''.$x.'\');">Delete</button><input type="checkbox" value="'.$user['id'].'" id="'.$x.'" hidden></input></td></tr>';
        }
        echo '</table><button id="verAll" class="btn btn-success" onclick="verifyAll();">Verify Selected</button>';
    } else {
        echo '<p class="message">No new users!</p>';
    };


?>
    </table>
    </div>
    </form>
    </body>
</html>
