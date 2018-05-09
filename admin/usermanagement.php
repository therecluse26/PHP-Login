<?php
/**
* Page that allows admins to verify or delete new (unverified) users
**/
$userrole = 'Admin';
$title = 'Manage Active Users';
require '../login/misc/pagehead.php';
?>

<script type="text/javascript" src="../login/js/DataTables/datatables.min.js"></script>
<script type="text/javascript" src="../login/js/loadingoverlay.min.js"></script>
<link rel="stylesheet" type="text/css" href="../login/js/DataTables/datatables.min.css"/>
<link rel="stylesheet" type="text/css" href="css/usermanagement.css"/>

</head>
<body>
  <?php require 'login/misc/pullnav.php'; ?>
  <div class="container-fluid">
    <div class="row">
      <div class="col-sm-12">
        <h3>Manage Active Users</h3>

        <table id="userlist" class="table table-sm">
          <thead>
            <th></th>
            <th>Username</th>
            <th>Email</th>
            <th>Role(s)</th>
            <th>Timestamp</th>
          </thead>
        </table>
      </div>
    </div>
  </div>

<?php include "partials/usermanagementmodals.html";?>

  <script type="application/javascript" src="js/usermanagement.js"></script>

</body>
</html>
