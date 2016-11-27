<div class="container">
<form class="form-signup" id="resetform" name="resetform" method="post">
<h2 class="form-signup-heading">Reset Password</h2>
<br>
<input name="userid" id="userid" placeholder="User Id" value="<?php echo $_GET['id'];?>" hidden>
<input name="password1" id="password1" type="password" class="form-control" placeholder="New Password">
<input name="password2" id="password2" type="password" class="form-control" placeholder="Repeat Password">
<button name="Submit" id="submit" class="btn btn-lg btn-primary btn-block" type="submit">Reset</button>
<div id="message"></div>
</form>
</div>

<?php
$pagetype = "loginpage";
$title = "Reset Password";
require "partials/jsinclude.php";
?>
<script>

$( "#usersignup" ).validate({
  rules: {
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
