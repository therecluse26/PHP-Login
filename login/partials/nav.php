<div class="dropdown pull-right">
  <button class="btn btn-default dropdown-toggle" type="button" id="userDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><?php echo $_SESSION['username']; ?>
    <span class="caret"></span>
  </button>
  <ul class="dropdown-menu" aria-labelledby="userDropdown">
      
    <li><a href="#">Edit Profile</a></li>

    <!-- Admin Controls -->
    <?php if ((array_key_exists('admin', $_SESSION)) && $_SESSION['admin'] == 1): ?>
        <li><a href="<?php echo $url; ?>/admin/verifyusers.php">Verify/Delete Users</a></li>
    <?php endif; ?>

    <li role="separator" class="divider"></li>
    <li><a href="<?php echo $url; ?>/login/logout.php">Logout</a></li>

  </ul>
</div>