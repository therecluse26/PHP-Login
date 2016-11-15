<?php
$currdir = dirname(getcwd());
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Install PHP-Login</title>
    <meta name="Author" content=""/>
     <link rel="stylesheet" type="text/css" href="../login/css/main.css"> 
    <link rel="stylesheet" type="text/css" href="bootstrap/bootstrap.css">
    <script src="http://code.jquery.com/jquery-3.1.0.min.js"></script>
    <script src="bootstrap/bootstrap.js"></script>
    <script src="ajax/instvalidate.js"></script>
</head>
<body>

<div class="table">
<table class="table table-bordered table-striped table-highlight">
<tr>

<form id="dbform" action="install.php" class="form-signin" method="post">

<td>
<label for="dbhost">Database Settings:</label>
        <input name="dbhost" id="dbhost" class="form-control" placeholder="Database Hostname"></input><br>
        <input name="dbuser" id="dbuser" class="form-control" placeholder="Username"></input><br>
        <input name="dbpw" id="dbpw" class="form-control" placeholder="Password"></input><br>
        <input name="dbname" id="dbname" class="form-control" placeholder="Database Name"></input><br>
        <input name="tblprefix" id="tblprefix" class="form-control" placeholder="Table prefix"></input><br>
</td>
<td>
<label for="root_dir" id="valstatus">Root Path:</label>
        <input name="root_dir" id="root_dir" class="form-control" placeholder="Site Root Directory" value=<?php echo $currdir; ?>></input><br>


<label for="superadmin">Superadmin Settings:</label>
        <input name="superadmin" id="superadmin" class="form-control" placeholder="Superadmin username"></input><br>
        <input type="password" name="sapw" id="sapw" class="form-control" placeholder="Superadmin Password"></input><br>
        <input name="saemail" id="saemail" class="form-control" placeholder="Superadmin Email"></input><br>
</td>

<td>
<label for="smtp_fromEmail">SMTP Settings:</label>
        <input name="smtp_fromEmail" id="smtp_fromEmail" class="form-control" placeholder="'From' Email"></input><br>
        <input name="smtp_fromName" id="smtp_fromName" class="form-control" placeholder="'From' Name"></input><br>
        <input name="smtp_server" id="smtp_server" class="form-control" placeholder="SMTP Server"></input><br>
        <input name="smtp_user" id="smtp_user" class="form-control" placeholder="SMTP Username"></input><br>
        <input name="smtp_pw" id="smtp_pw" class="form-control" placeholder="SMTP Password"></input><br>
        <input name="smtp_port" id="smtp_port" class="form-control"  placeholder="Port"></input><br>
        <select name="smtp_security" id="smtp_security" >
            <option value="">-- SMTP Security --</option>
            <option value="tls">TLS</option>
            <option value="ssl">SSL</option>
        </select>
</td>


</tr>

    </table>
    <tr>
    <button id="submitbtn">Submit</button>
</tr>
</div>

</form>

</body>
</html>
