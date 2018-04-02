<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$currdir = dirname(getcwd());
$basedir = dirname(dirname(getcwd()));
if ($_SERVER['SERVER_PORT'] != 80) {
    $baseurl = dirname(dirname(dirname('http://' . $_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'] . $_SERVER['REQUEST_URI'])));
} else {
    $baseurl = dirname(dirname(dirname('http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'])));
}

$sa_id = uniqid(rand(), false);

//Check if cURL is enabled
function func_enabled($function)
{
    $disabled = explode(',', ini_get('disable_functions'));
    return !in_array($function, $disabled);
}
if (func_enabled('shell_exec')) {
    if (substr(PHP_OS, 0, 3) != 'WIN') {
        if (!shell_exec('which curl')) {
            $curl_enabled = 'false';
        } else {
            $curl_enabled = 'true';
        }
    } else {
        if (!shell_exec('where curl')) {
            $curl_enabled = 'false';
        } else {
            $curl_enabled = 'true';
        }
    }
} else {
    $curl_enabled = 'false';
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
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="../js/jquery.min.js"></script>
    <script src="../bootstrap/bootstrap.js"></script>
    <script src="../js/jquery.validate.min.js"></script>
    <link rel="stylesheet" href="../bootstrap/bootstrap.css"></link>
    <link rel="stylesheet" type="text/css" href="../../login/css/main.css">
    <title>Manual Install - Start Here</title>
</head>

<body>
    <div class="container">
        <h3>PHP-Login Manual Installation</h3>
        <div class="col-md-4">

            <form class="manInstForm form-horizontal" id="manInstForm" action="#">
                <div class="form-group">
                    <label for="base_dir">Base Directory</label>
                    <input class="form-control" name="base_dir" id="base_dir" placeholder="Base Directory" value="<?php echo $basedir; ?>" required></input>
                    <br>
                    <label for="base_url">Base Website URL</label>
                    <input class="form-control" name="base_url" id="base_url" placeholder="Base URL" value="<?php echo $baseurl; ?>" required></input>
                </div>
                <div class="form-group">
                    <input class="form-control" name="db_host" id="db_host" placeholder="Database Host" required></input>
                    <input class="form-control" name="db_name" id="db_name" placeholder="Database Name" required></input>
                    <input class="form-control" name="db_user" id="db_user" placeholder="Database User" required></input>
                    <input class="form-control" name="db_pw" id="db_pw" placeholder="Database Password" required></input>

                    <input class="form-control" name="site_name" id="site_name" placeholder="Website Name" required></input>
                    <input class="form-control" name="curl_enabled" id="curl_enabled" placeholder="Curl Enabled" value="<?php echo $curl_enabled;?>" style="display:none;"></input>
                    <input class="form-control" name="mail_server" id="mail_server" placeholder="Mail Server" required></input>
                    <input class="form-control" name="mail_user" id="mail_user" placeholder="Mail Username" required></input>
                    <input class="form-control" name="mail_pw" id="mail_pw" placeholder="Mail Password" required></input>
                    <input class="form-control" name="sa_user" id="sa_user" placeholder="Superadmin Username" required></input>
                    <input class="form-control" name="sa_password" id="sa_password" placeholder="Password" required></input>
                    <input type="email" class="form-control" name="sa_email" id="sa_email" placeholder="Superadmin Email" required></input>
                    <input class="form-control" name="sa_id" id="sa_id" placeholder="Superadmin Id" value="<?php echo $sa_id; ?>" style="display:none;"></input>
                    <br>
                    <button class="btn btn-primary" type="button" id="pwGenBtn">Generate Configuration</button>
                </div>
            </form>
        </div>
        <div class="col-md-8">
            <div id="sqlresult"></div>
            <div id="dbconfresult"></div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            $("#pwGenBtn").click(function () {
                if (!$("#manInstForm").valid()) {
                    return false;
                }
                else {
                    var base_dir = $("#base_dir").val();
                    var base_url = $("#base_url").val();
                    var site_name = $("#site_name").val();
                    var curl_enabled = $("#curl_enabled").val();
                    var mail_server = $("#mail_server").val();
                    var mail_user = $("#mail_user").val();
                    var mail_pw = $("#mail_pw").val();
                    var sa_user = $("#sa_user").val();
                    var sa_id = $("#sa_id").val();
                    var sa_email = $("#sa_email").val();
                    var sa_password = $("#sa_password").val();
                    var db_host = $("#db_host").val();
                    var db_user = $("#db_user").val();
                    var db_pw = $("#db_pw").val();
                    var db_name = $("#db_name").val();

                    $.ajax({
                        type: "POST"
                        , url: "ajax/sqlgen.php"
                        , data: {
                            db_name: db_name
                            , base_dir: base_dir
                            , base_url: base_url
                            , site_name: site_name
                            , curl_enabled: curl_enabled
                            , mail_server: mail_server
                            , mail_user: mail_user
                            , mail_pw: mail_pw
                            , sa_user: sa_user
                            , sa_id: sa_id
                            , sa_email: sa_email
                            , sa_password: sa_password
                        }
                        , dataType: 'HTML'
                        , success: function (html) {
                            $("#sqlresult").html(html);
                        }
                    });

                    $.ajax({
                        type: "POST"
                        , url: "ajax/dbconfgen.php"
                        , data: {
                            db_host: db_host
                            , db_user: db_user
                            , db_pw: db_pw
                            , db_name: db_name
                            , base_url: base_url
                            , sa_user: sa_user
                        }
                        , dataType: 'HTML'
                        , success: function (html) {
                            $("#dbconfresult").html(html);
                        }
                    });

                }
            });
        });
    </script>
</body>
</html>
