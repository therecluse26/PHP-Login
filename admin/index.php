<?php
$userrole = 'Admin';
$title = 'Admin Panel';
include "../login/misc/pagehead.php";
?>
</head>
<body>

<?php require '../login/misc/pullnav.php'; ?>

<div class="container">

  <div class="jumbotron text-center"><h1>Admin Panel</h1>
    <p>Carry out numerous administrative tasks</p></div>
    <div class="col-lg-2"></div><div class="col-lg-8">
    <h2>Available functions:</h2>

<ul class="list-group">
    <!-- Superadmin Controls -->
    <?php if ($auth->isSuperAdmin()): ?>
      <li class="list-group-item h4"><a href="<?php echo $conf->base_url; ?>/admin/config.php">Edit Site Config</a></li>
      <li class="list-group-item h4"><a href="<?php echo $conf->base_url; ?>/admin/permissions.php">Manage Permissions</a></li>
    <?php endif; ?>
    <!-- Admin Controls -->
    <?php if ($auth->isAdmin()): ?>
      <li class="list-group-item h4"><a href="<?php echo $conf->base_url; ?>/admin/users.php">Manage Users</a></li>
      <li class="list-group-item h4"><a href="<?php echo $conf->base_url; ?>/admin/roles.php">Manage Roles</a></li>
      <li class="list-group-item h4"><a href="<?php echo $conf->base_url; ?>/admin/mail.php">Mail Log</a></li>
    <?php endif; ?>

</ul>
    </div><div class="col-lg-2"></div>
  </div>
</body>
</html>
