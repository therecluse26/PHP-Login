<?php
$title = "Standard User Page";
$userrole = "Standard User"; // Allow only logged in users
include "login/misc/pagehead.php";
?>
</head>
<body>
  <?php require 'login/misc/pullnav.php'; ?>
    <div class="container">

        <h2>Standard User Page</h2>
        <p>Hello, <?=$_SESSION["username"]?>!</p>
        <p>This page requires a Standard User to be logged in</p>
    </div>
</body>
</html>
