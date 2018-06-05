<?php
$userrole = 'Standard User';
$title = 'Account Settings';
require '../login/misc/pagehead.php';
$uid = $_SESSION['uid'];
$usr = PHPLogin\UserHandler::pullUserById($uid);
?>
<script src="js/accountupdate.js"></script>
<script src="../login/js/jquery.validate.min.js"></script>
<script src="../login/js/additional-methods.min.js"></script>

</head>
<body>
  <?php require '../login/misc/pullnav.php'; ?>
    <div class="container">
        <div class="col-sm-4"></div>
        <div class="col-sm-4">
            <h2><?php echo $title;?></h2>
            <form id="profileForm" enctype="multipart/form-data">
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-12">
                            <label for="username" class="label label-default">Username</label>
                            <input type="text" class="form-control" name="username" id="username" value="<?php echo $usr['username']; ?>" disabled>
                            <label for="email" class="label label-default">Email</label>
                            <input type="text" class="form-control" name="email" id="email" value="<?php echo $usr['email']; ?>"> </div>
                    </div>
                </div>
                <h4 class="form-signup-heading">Reset Password</h4>
                <input name="id" id="id" placeholder="User Id" value="<?php echo $uid;?>" hidden>
                <label for="password1" class="label label-default">Password</label>
                <input name="password1" id="password1" type="password" class="form-control" placeholder="New Password">
                <br>
                <label for="password2" class="label label-default">Repeat Password</label>
                <input name="password2" id="password2" type="password" class="form-control" placeholder="Repeat Password">
                <br/>
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-12">
                            <div id="message"></div>
                            <button type="submit" class="btn btn-lg btn-primary btn-block" id="submitbtn">Submit</button>
                        </div>
                    </div>
            </form>
            </div>
            <input id="emailorig" value="<?php echo $usr['email']; ?>" hidden disabled></input>
        </div>
        <div class="col-sm-4"></div>
    </div>
    <script>
        $("#profileForm").validate({
            rules: {
                password1: {
                    <?php if ((bool) $conf->password_policy_enforce == true) {
    echo "minlength: ". $conf->password_min_length;
};?>
                }
                , password2: {
                     equalTo: "#password1"
                }
            }
        });
    </script>
</body>
</html>
