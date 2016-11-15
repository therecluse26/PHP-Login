<nav class="navbar navbar-default navbar-static-top">
    <div class="container-fluid">

<!-- Admin Controls -->
<?php if ((array_key_exists('admin', $_SESSION)) && $_SESSION['admin'] == 1): ?>
    <ul class="nav navbar-nav">
        <li><a href="<?php echo $url; ?>/login/admin/adminverify.php">Verify Users</a></li>
    </ul>
<?php endif; ?>

    <ul class="nav navbar-nav navbar-right">
        <li><?php echo $_SESSION['username']; ?></li>
        <li><a href="<?php echo $url; ?>/login/logout.php">Logout</a></li>
    </ul>
    </div>
</nav>