<?php
$title = "Public Page";
include "login/misc/pagehead.php";
?>
</head>
<body>
  <?php require 'login/misc/pullnav.php'; ?>
    <div class="container">

        <h2>Public Page</h2>
        <p>
          This page doesn't require being logged in!
          <br><br>
          The <?php echo highlight_string("<?php require 'login/misc/pullnav.php';?>", true);?>
          tag can be removed if you don't want the navbar
          at the top of the page.
        </p>

    </div>
</body>
</html>
