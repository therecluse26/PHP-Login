<?php
$usr = profileData::pullUserFields($_SESSION['uid'], Array('firstname', 'lastname'));

if (is_array($usr) && trim($usr['firstname']) != '' && trim($usr['lastname']) != '') {
    $user = $usr['firstname']. ' ' .$usr['lastname'];
} else {
    $user = $_SESSION['username'];
}

if (($mainlogo == '') || @get_headers($mainlogo)[0] == 'HTTP/1.1 404 Not Found') {
    $site_logo = '';
} else {
    $site_logo = '<div class="pull-left"><a href="'.$this->base_url.'"><img width="200px" src="'.$mainlogo.'"></img></a></div>';
    echo $site_logo;
}
?>
<div class="dropdown pull-right">

  <button class="btn btn-default dropdown-toggle" type="button" id="userDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><?php echo $user; ?>
    <span class="caret"></span>
  </button>
  <ul class="dropdown-menu" aria-labelledby="userDropdown">

    <li><a href="<?php echo $this->base_url; ?>/user/profileedit.php">Edit Profile</a></li>

    <li><a href="<?php echo $this->base_url; ?>/user/accountedit.php">Account Settings</a></li>


    <!-- Admin Controls -->
    <?php if ((array_key_exists('admin', $_SESSION)) && $_SESSION['admin'] == 1): ?>
          <li role="separator" class="divider"></li>

        <li><a href="<?php echo $this->base_url; ?>/admin/verifyusers.php">Verify/Delete Users</a></li>
    <?php endif; ?>

          <!-- Superadmin Controls -->
    <?php if ((array_key_exists('superadmin', $_SESSION)) && $_SESSION['superadmin'] == 1): ?>

        <li><a href="<?php echo $this->base_url; ?>/admin/editconfig.php">Edit Site Config</a></li>
    <?php endif; ?>

    <li role="separator" class="divider"></li>
    <li><a href="<?php echo $this->base_url; ?>/login/logout.php">Logout</a></li>

  </ul>
</div>

