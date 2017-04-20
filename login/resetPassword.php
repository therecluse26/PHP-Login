<?php
session_start();
if (isset($_SESSION['username'])) {
    header("location:../index.php");
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Reset Password</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="../css/bootstrap.css" rel="stylesheet" media="screen">
    <link href="../css/main.css" rel="stylesheet" media="screen">
  </head>

  <body>
    <div class="container">

      <form class="form-resetpassword" name="form1" method="post" action="checkreset.php" id="form">
        <h2 class="form-resetpassword-heading">Reset Password</h2>
        <input name="email" id="email" type="text" class="form-control" placeholder="Email" autofocus>
        <button name="Submit" id="submit" class="btn btn-lg btn-primary btn-block" type="submit">Reset Password</button>

        <div id="message"></div>
      </form>

    </div> <!-- /container -->

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="js/jquery-2.2.4.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script type="text/javascript" src="js/bootstrap.js"></script>
    <!-- The AJAX login script -->
    <script src="js/resetPassword.js"></script>

  </body>
</html>
