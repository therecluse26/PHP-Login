<?php
$title = "Admin Page";
$userrole = "Admin"; // Allow only admins to access this page
include "login/misc/pagehead.php";
?>
</head>
<body>
  <?php require 'login/misc/pullnav.php'; ?>
    <div class="container">

        <h2>Admin Page</h2>
        <p>Hello, <?=$_SESSION["username"]?>!</p>
        <p>This page requires an Admin user to be logged in</p>
    </div>
</body>
</html>
