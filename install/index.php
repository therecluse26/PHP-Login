<?php
$currdir = dirname(getcwd());
$baseurl = dirname('http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']);

if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {

    $serveruser = get_current_user();
    $fileowner = fileowner($currdir);

} else {

    $serveruser = posix_geteuid();
    $fileowner = fileowner($currdir);

}

//Checks folder owner and permissions
if ($serveruser != $fileowner) {
    echo "<div class=\"alert alert-danger alert-dismissable\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>WARNING: Folder owner should be same as server user! Current server user: <b>" . $serveruser . "</b><br> Please run the following command (on unix-based systems) and refresh the page<br>";

    echo "<b>sudo chown -R " . $serveruser .":". $serveruser ." ". dirname(dirname(__FILE__)) . "</b></div>";

} else if (!is_writable($currdir)) {
    echo "<div class=\"alert alert-danger alert-dismissable\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>WARNING: " . $currdir . " is not writable! Current permissions: <b>" . substr(sprintf("%o",fileperms($currdir)),-4)."</b><br>Please run the following terminal command and refresh the page: <br>";
    echo "<b>sudo chmod -R 755 " . dirname(dirname(__FILE__)). "</b></div>";

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
        <script src="http://code.jquery.com/jquery-3.1.0.min.js"></script>
        <script src="js/jquery.validate.min.js"></script>
        <script src="bootstrap/bootstrap.js"></script>
        <script src="ajax/instvalidate.js"></script>
    </head>

    <body>
        <div class="container">
            <table class="table table-bordered table-striped table-highlight">

                <h1>Install PHP-Login</h1>

                <form id="dbform" action="install.php" class="form-signin" method="post">

                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label for="dbhost" id="dbhost">Database Hostname</label>
                            <input name="dbhost" id="dbhost" class="form-control" placeholder="Database Hostname"></input>
                        </div>
                        <div class="col-lg-6">
                            <label for="dbuser" id="dbuser">DB User</label>
                            <input name="dbuser" id="dbuser" class="form-control" placeholder="Username"></input>
                            <br>
                        </div>
                        <div class="col-lg-6">
                            <label for="dbpw" id="dbpw">DB Password</label>
                            <input name="dbpw" id="dbpw" class="form-control" placeholder="Password"></input>
                            <br>
                        </div>
                        <div class="col-lg-6">
                            <label for="dbname" id="dbname">DB Name</label>
                            <input name="dbname" id="dbname" class="form-control" placeholder="Database Name"></input>
                            <br>
                        </div>
                        <div class="col-lg-6">
                            <label for="tblprefix" id="tblprefix">DB Table Prefix</label>
                            <input name="tblprefix" id="tblprefix" class="form-control" placeholder="Table prefix"></input>
                            <br>
                        </div>

                    </div>

                    <div class="form-group row">

                        <div class="col-lg-6">

                            <label for="base_dir" id="valstatus">Root Install Path:</label>
                            <input name="base_dir" id="base_dir" class="form-control" placeholder="Site Root Directory" value=<?php echo $currdir; ?>></input>
                            <br>
                        </div>
                        <div class="col-lg-6">
                            <label for="base_url" id="valstatus">Base Url:</label>
                            <input name="base_url" id="base_url" class="form-control" placeholder="Site Base Url" value=<?php echo $baseurl; ?>></input>
                            <br>
                        </div>
                        <div class="col-lg-6">

                            <label for="superadmin">Superadmin Username:</label>
                            <input name="superadmin" id="superadmin" class="form-control" placeholder="Superadmin username"></input>
                            <br>
                        </div>

                        <div class="col-lg-6">

                            <label for="saemail">Superadmin Email:</label>
                            <input name="saemail" id="saemail" class="form-control" placeholder="Superadmin Email"></input>
                            <br>
                        </div>

                        <div class="col-lg-6">
                            <label for="sapw">New Password:</label>
                            <input type="password" name="sapw" id="sapw" class="form-control" placeholder="Superadmin Password"></input>
                            <br>
                        </div>
                        <div class="col-lg-6">
                            <label for="sapw2">Repeat Password:</label>
                            <input type="password" name="sapw2" id="sapw2" class="form-control" placeholder="Superadmin Password"></input>
                            <br>
                        </div>

                        <div class="col-lg-6">

                            <button id="submitbtn" class="btn btn-primary">Submit</button>

                        </div>
                    </div>

                </form>

        <script>
            $("#dbform").validate({
                rules: {
                    superadmin: {required: true},

                    saemail: {required: true, email: true},

                    sapw: {required: true},

                    sapw2: {
                        equalTo: "#sapw"
                    }
                }
            });
        </script>
    </body>
    </html>
