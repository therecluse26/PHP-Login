<?php
$userrole = 'loginpage';
$title = 'Login';
include 'misc/pagehead.php';
?>
</head>
<body>
  <?php require 'misc/pullnav.php'; ?>
    <div class="container logindiv">
        <div class="col-sm-4"></div>
        <div class="col-sm-4">
            <form class="text-center" name="loginform" method="post" action="ajax/checklogin.php">
                <h3 class="form-signin-heading"><?php echo $title;?></h3>
                <br>
                <div class="form-group">
                    <input name="myusername" id="myusername" type="text" class="form-control input-lg" placeholder="Username" autofocus>
                    <input name="mypassword" id="mypassword" type="password" class="form-control input-lg" placeholder="Password"> </div>
                <div class="form-group">
                    <button name="Submit" id="submit" class="btn btn-lg btn-primary btn-block" type="submit">Log In</button>
                </div>
                <div class="form-group">
                    <div class="checkbox">
                        <input id="remember"  type="checkbox"> Remember Me</input>
                    </div>
                </div>
            </form>
            <div id="message"></div>
            <p class="text-center"><a href="forgotpassword.php">Forgot Password?</a></p>
            <p class="text-center">or <a href="signup.php">Create an Account</a></p>
        </div>
        <div class="col-sm-4"></div>
    </div>
    <!-- The AJAX login script -->
    <script src="js/login.js"></script>
</body>
</html>
