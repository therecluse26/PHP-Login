<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$currdir = dirname(getcwd());

if ($_SERVER['SERVER_PORT'] != 80) {
    $baseurl = dirname('http://' . $_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'] . $_SERVER['REQUEST_URI']);
} else {
    $baseurl = dirname('http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']);
}

if (function_exists('posix_geteuid')) {
    $serveruser = posix_geteuid();
} else {
    if (function_exists('get_current_user')) {
        $serveruser = get_current_user();
    } else {
        echo "<div class=\"alert alert-danger alert-dismissable\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>Process functions 'posix_geteuid() and/or get_current_user() not enabled</b><br> Please run the following command (on unix-based systems) and refresh the page<br>";
    }
}

$fileowner = fileowner($currdir);

//Checks folder owner and permissions
if ($serveruser != $fileowner) {
    echo "<div class=\"alert alert-danger alert-dismissable\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>WARNING: Folder owner should be same as server user! Current server user: <b>" . $serveruser . "</b><br> Please run the following command (on unix-based systems) and refresh the page<br>";

    echo "<b>sudo chown -R " . $serveruser .":". $serveruser ." ". dirname(dirname(__FILE__)) . "</b></div>";

} elseif (!is_writable($currdir)) {
    
    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
        //Outputs appropriate fix for Windows machines
        echo "<div class=\"alert alert-danger alert-dismissable\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>WARNING: " . $currdir . " is not writable! Current permissions: <b>" . substr(sprintf("%o", fileperms($currdir)), -4)."</b><br>Enable read, write and execute permissions on this folder: " . dirname(dirname(__FILE__)) . " to ". $serveruser ."</b></div>";
        
    } else {
        //Outputs appropriate fix for Unix-based machines
        echo "<div class=\"alert alert-danger alert-dismissable\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>WARNING: " . $currdir . " is not writable! Current permissions: <b>" . substr(sprintf("%o", fileperms($currdir)), -4)."</b><br>Please run the following terminal command and refresh the page: <br>";
        echo "<b>sudo chmod -R 755 " . dirname(dirname(__FILE__)) ." && sudo chown -R ". $serveruser ." ". dirname(dirname(__FILE__)) ."</b></div>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Install PHP-Login</title>
    <meta name="Author" content="" />
    <link rel="stylesheet" type="text/css" href="bootstrap/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="../login/css/main.css">
    <script src="js/jquery.min.js"></script>
    <script src="js/jquery.validate.min.js"></script>
    <script src="bootstrap/bootstrap.js"></script>
    <script src="ajax/instvalidate.js"></script>
    <script src="js/modalInstallSelect.js"></script>
</head>

<body>
    <div class="container">
        <table class="table table-bordered table-striped table-highlight">

            <h1>Install PHP-Login</h1>

            <form id="dbform" action="install.php" class="form-signin" method="post">

                <div class="form-group row">
                    <div class="col-sm-6">
                        <label for="dbhost" id="dbhost">Database Hostname</label>
                        <input name="dbhost" id="dbhost" class="form-control" placeholder="Database Hostname" required></input>
                        <br>
                    </div>
                    <div class="col-sm-6">
                        <label for="dbuser" id="dbuser">DB User</label>
                        <input name="dbuser" id="dbuser" class="form-control" placeholder="Username" required></input>
                        <br>
                    </div>
                    <div class="col-sm-6">
                        <label for="dbpw" id="dbpw">DB Password</label>
                        <input name="dbpw" id="dbpw" class="form-control" placeholder="Password"></input>
                        <br>
                    </div>
                    <div class="col-sm-6">
                        <label for="dbname" id="dbname">DB Name</label>
                        <input name="dbname" id="dbname" class="form-control" placeholder="Database Name" required></input>
                        <br>
                    </div>
                    <div class="col-sm-6">
                        <label for="tblprefix" id="tblprefix">DB Table Prefix</label>
                        <input name="tblprefix" id="tblprefix" class="form-control" placeholder="Table prefix"></input>
                        <br>
                    </div>

                </div>

                <div class="form-group row">

                    <div class="col-sm-6">

                        <label for="base_dir" id="valstatus">Root Install Path:</label>
                        <input name="base_dir" id="base_dir" class="form-control" placeholder="Site Root Directory" value=<?php echo $currdir; ?> required></input>
                        <br>
                    </div>
                    <div class="col-sm-6">
                        <label for="base_url" id="valstatus">Base Url:</label>
                        <input name="base_url" id="base_url" class="form-control" placeholder="Site Base Url" value=<?php echo $baseurl; ?> required></input>
                        <br>
                    </div>
                    <div class="col-sm-6">

                        <label for="superadmin">Superadmin Username:</label>
                        <input name="superadmin" id="superadmin" class="form-control" placeholder="Superadmin username" required></input>
                        <br>
                    </div>

                    <div class="col-sm-6">

                        <label for="saemail">Superadmin Email:</label>
                        <input type="email" name="saemail" id="saemail" class="form-control" placeholder="Superadmin Email" required></input>
                        <br>
                    </div>

                    <div class="col-sm-6">
                        <label for="sapw">New Password:</label>
                        <input type="password" name="sapw" id="sapw" class="form-control" placeholder="Superadmin Password" required></input>
                        <br>
                    </div>
                    <div class="col-sm-6">
                        <label for="sapw2">Repeat Password:</label>
                        <input type="password" name="sapw2" id="sapw2" class="form-control" placeholder="Superadmin Password" required></input>
                        <br>
                    </div>

        <div class="col-sm-6">

            <input id="submitbtn" type="submit" class="btn btn-primary"></input>
            </form>

        </div>
    </div>
</div>


<!-- Modal -->
<div id="instTypeModal" class="modal fade largeModal" data-backdrop="static" data-keyboard="false" role="dialog">
    
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <h2 class="modal-title text-center">Installation Type</h2>
          </div>


            <div class="modal-body">
                <div class="container">
                    <div class="col-md-6">
                        <button type="button" class="btn btn-primary btn-block btn-lg" data-dismiss="modal">Automated</button>
                    </div>
                    <div class="col-md-6">
                        <form action="manual">
                            <button type="submit" class="btn btn-primary btn-block btn-lg">Manual</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
