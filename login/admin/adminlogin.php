<?php

if (array_key_exists('admin',$_SESSION) && $_SESSION['ip_address'] == getenv ( "REMOTE_ADDR" )) {
    header("location:adminverify.php");
}
chdir('../');

$page = 'adminlogin';
$title = 'Admin Login';
include 'partials/pagehead.php';
?>
    <div class="container">

      <form class="form-signin" name="form1" method="post" action="../checklogin.php">
        <h2 class="form-signin-heading">Please sign in</h2>
        <input name="myusername" id="myusername" type="text" class="form-control" placeholder="Username" autofocus>
        <input name="mypassword" id="mypassword" type="password" class="form-control" placeholder="Password">
        <!-- The checkbox remember me is not implemented yet...
        <label class="checkbox">
          <input type="checkbox" value="remember-me"> Remember me
        </label>
        -->
        <button name="Submit" id="submit" class="btn btn-lg btn-primary btn-block" type="submit">Admin Sign in</button>

        <div id="message"></div>
      </form>

    </div> <!-- /container -->

    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <!-- The AJAX login script -->
    <script src="js/adminlogin.js"></script>

  </body>
</html>
