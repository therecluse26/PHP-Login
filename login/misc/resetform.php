<form class="form-signup" id="resetform" name="resetform" method="post">
    <h3 class="form-signup-heading">Reset Password</h3>
    <br>
    <input name="password1" id="password1" type="password" class="form-control" placeholder="New Password">
    <input name="password2" id="password2" type="password" class="form-control" placeholder="Repeat Password">
    <button name="Submit" id="submit" class="btn btn-lg btn-primary btn-block" type="submit">Reset</button>
    <input name="t" id="t" value="<?php echo $jwt;?>" hidden>
</form>
<script>
$("#resetform").validate({
    rules: {
        password1: {
            required: true <?php if ($conf->password_policy_enforce == "true") {
    echo ", minlength: ". $conf->password_min_length;
};?>
        }
        , password2: {
            required: true
            , equalTo: "#password1"
        }
    }
});
</script>
