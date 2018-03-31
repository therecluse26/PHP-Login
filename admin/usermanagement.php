<?php
/**
* Page that allows admins to verify or delete new (unverified) users
**/
$pagetype = 'adminpage';
$title = 'Manage Active Users';
require '../login/misc/pagehead.php';
$users = UserData::getAllActiveUsers();
$x = 0;
?>

<link rel="stylesheet" type="text/css" href="../login/js/DataTables/datatables.min.css"/>
<script type="text/javascript" src="../login/js/DataTables/datatables.min.js"></script>

</head>
<body>
  <?php require 'login/misc/pullnav.php'; ?>
  <div class="container">
    <div class="row">
      <div class="col-sm-12">
        <h3>Manage Active Users</h3>

<?php

    if (!empty($users)) {
        echo '<table id="userlist" class="table table-sm">
                <thead class="headrow">
                  <th>Username</th>
                  <th>Email</th>
                  <th>Role(s)</th>
                  <th>Timestamp</th>
                  <th><button class="btn btn-info btn-sm pull-right">Select All</button>
                      <input type="checkbox" id="selectAll" hidden></input>
                  </th>
                </thead>';

        foreach ($users as $user) {
            $x++;
            echo '<tr class="datarow" scope="row" id="row'.$x.'">
                    <td>'.$user['username'].'</td>
                    <td>'.$user['email'].'</td>
                    <td>'.$user['roles'].'</td>
                    <td>'.$user['timestamp'].'</td>
                    <td><button id="delbutton'.$x.'" class="btn btn-danger btn-sm btn-fixed pull-right"
                            onclick="deleteUser(\''.$user['id'].'\',\''.$user['email'].'\',\''.$user['username'].'\',\''.$x.'\');">Delete</button>
                        <input type="checkbox" value="'.$user['id'].'" id="'.$x.'" hidden></input>
                    </td>
                  </tr>';
        }
    } else {
        echo '<p class="message">No active users</p>';
    };


?>
        </table>
      </div>
    </div>
  </div>
  <script>
    $(document).ready(function() {
        $('#userlist').DataTable();
    });
  </script>
</body>
</html>
