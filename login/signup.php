<?php
if ((isset($_SESSION)) && array_key_exists('username', $_SESSION) ) {
  session_destroy();
}
$title = 'Sign Up';
$pagetype = 'loginpage';
require 'partials/pagehead.php';
?>
<body>
<div class="container">

      <form class="form-signup" id="usersignup" name="usersignup" method="post" action="createuser.php">
        <h2 class="form-signup-heading">Register</h2>
        <input name="newuser" id="newuser" type="text" class="form-control" placeholder="Username" autofocus>
        <input name="email" id="email" type="text" class="form-control" placeholder="Email">
<br>
        <input name="password1" id="password1" type="password" class="form-control" placeholder="Password">
        <input name="password2" id="password2" type="password" class="form-control" placeholder="Repeat Password">

        <button name="Submit" id="submit" class="btn btn-lg btn-primary btn-block" type="submit">Sign up</button>

        <div id="message"></div>
      </form>

    </div> <!-- /container -->


<script>

$( "#usersignup" ).validate({
  rules: {
    email: {
        email: true,
        required: true
    },
    password1: {
      required: true,
      minlength: 4
    },
    password2: {
      equalTo: "#password1"
    }
  }
});
</script>
</body>
</html>
