<?php
/**
* Page that allows admins to manage roles
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
  <?php require '../login/misc/pullnav.php'; ?>
  <div class="container-fluid">
    <div class="row">
      <div class="col-sm-12">
        <h3>Manage Roles</h3>

        <table id="roleList" class="table table-sm">
          <thead>
            <th>Id</th>
            <th>Role Name</th>
            <th>Description</th>
            <th>User Count</th>
            <th>Assign Users</th>
            <th>Edit</th>
          </thead>
        </table>
      </div>
    </div>
  </div>

  <!-- New Role Modal -->
  <div class="modal fade" id="newRole">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">New Role<button type="button" class="close" data-dismiss="modal">&times;</button></h4>

        </div>
        <div class="modal-body">
          <form id="newRoleForm">
            <label for="Name">Role Name</label>
            <input class="form-control" name="roleName" id="new_RoleName"></input>
            <label for="Description">Role Description</label>
            <input class="form-control" name="roleDescription" id="new_RoleDescription"></input>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-danger float-left" data-dismiss="modal">Close</button>
          <input type="submit" class="btn btn-success" id="submitNewRole"></input>
        </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Edit Role Modal -->
  <div class="modal fade" id="editRole">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Edit Role<button type="button" class="close" data-dismiss="modal">&times;</button></h4>

        </div>
        <div class="modal-body">
          <form id="editRoleForm">
            <input type="hidden" id="edit_role_id"></input>
            <label for="editRoleName">Role Name</label>
            <input class="form-control" name="editRoleName" id="edit_RoleName"></input>
            <label for="editRoleDescription">Role Description</label>
            <input class="form-control" name="editRoleDescription" id="edit_RoleDescription"></input>
        </div>
        <!-- Modal footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-danger float-left" data-dismiss="modal">Close</button>
          <input type="submit" class="btn btn-success" id="updateRole"></input>
        </form>
        </div>
      </div>
    </div>
  </div>

<?php include "partials/rolemanagementmodals.html";?>

<script type="application/javascript" src="js/rolemanagement.js"></script>

</body>
</html>
