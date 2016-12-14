<?php
$usr = profileData::pullUserFields($_SESSION['uid'], Array('firstname', 'lastname'));
if(is_array($usr)){
    $user = $usr['firstname']. ' ' .$usr['lastname'];
} else {
    $user = $_SESSION['username'];
}
?>
<div class="dropdown pull-right">

  <button class="btn btn-default dropdown-toggle" type="button" id="userDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><?php echo $user; ?>
    <span class="caret"></span>
  </button>
  <ul class="dropdown-menu" aria-labelledby="userDropdown">

    <li><a href="<?php echo $url; ?>/user/profileedit.php">Edit Profile</a></li>

    <li><a href="<?php echo $url; ?>/user/accountedit.php">Account Settings</a></li>


    <!-- Admin Controls -->
    <?php if ((array_key_exists('admin', $_SESSION)) && $_SESSION['admin'] == 1): ?>
          <li role="separator" class="divider"></li>

        <li><a href="<?php echo $url; ?>/admin/verifyusers.php">Verify/Delete Users</a></li>
    <?php endif; ?>

    <li role="separator" class="divider"></li>
    <li><a href="<?php echo $url; ?>/login/logout.php">Logout</a></li>

  </ul>
</div>
