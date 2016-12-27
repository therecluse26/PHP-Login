<?php
$title = 'Home';
include "login/partials/pagehead.php";
?>
</head>
<body>
  <div class="container">
      <h1 class="display-1">Homepage</h1>

    <?php if (isset($_SESSION['username'])){

        echo "<p class='lead'>Hi, ".$_SESSION['username']."!</p>";
    }; ?>

      This is your homepage. You can sign in or create a new account by clicking "Sign In" in the top right corner!


  </div>
</body>
</html>
