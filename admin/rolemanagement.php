<?php
/**
* Page that allows admins to verify or delete new (unverified) users
**/
$userrole = 'Admin';
$title = 'Manage Roles';
require '../login/misc/pagehead.php';
$x = 0;

?>

<script type="text/javascript" src="../login/js/DataTables/datatables.min.js"></script>
<script type="text/javascript" src="../login/js/loadingoverlay.min.js"></script>
<script type="text/javascript" src="../login/js/multiselect.min.js"></script>
<link rel="stylesheet" type="text/css" href="../login/js/DataTables/datatables.min.css"/>
<link rel="stylesheet" type="text/css" href="css/rolemanagement.css"/>

</head>
<body>
  <?php require 'login/misc/pullnav.php'; ?>
  <div class="container-fluid">
    <div class="row">
      <div class="col-sm-12">
        <h3>Manage Roles</h3>

        <table id="roleList" class="table table-sm">
          <thead>
            <th>Id</th>
            <th>Role Name</th>
            <th>Description</th>
            <th>Required</th>
            <th>Default</th>
            <th>Users</th>
          </thead>
        </table>
      </div>
    </div>
  </div>

<?php include "partials/rolemanagementmodals.html";?>

<script type="application/javascript" src="js/rolemanagement.js"></script>

</body>
</html>
