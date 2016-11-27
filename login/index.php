<?php
$pagetype = 'loginpage';
$title = 'Login';
include 'partials/pagehead.php';
?>
<body>
    <div class="container logindiv">

      <form class="form-signin loginbox" name="form1" method="post" action="checklogin.php">
        <h2 class="form-signin-heading"></h2>
        <input name="myusername" id="myusername" type="text" class="form-control" placeholder="Username" autofocus>
        <input name="mypassword" id="mypassword" type="password" class="form-control" placeholder="Password">
        <!-- The checkbox remember me is not implemented yet...
        <label class="checkbox">
          <input type="checkbox" value="remember-me"> Remember me
        </label>
        -->
        <button name="Submit" id="submit" class="btn btn-lg btn-primary btn-block" type="submit">Log In</button>

        <div id="message"></div>
      </form>

    </div> 
    
    <!-- The AJAX login script -->
    <script src="js/login.js"></script>

  </body>
</html>
