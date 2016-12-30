<?php
// Start the session.
session_start();


$dbhost = $_POST['dbhost'];
$dbuser = $_POST['dbuser'];
$dbpw = $_POST['dbpw'];
$dbname = $_POST['dbname'];
$tblprefix = $_POST['tblprefix'];

$base_dir = base64_encode($_POST['base_dir']);
$base_url = base64_encode($_POST['base_url']);

$superadmin = $_POST['superadmin'];
$saemail = $_POST['saemail'];
$said = uniqid(rand(), false);
$sapw = password_hash($_POST['sapw'], PASSWORD_DEFAULT);
$site_name = $_SERVER['SERVER_NAME'];

/*$dbhost = "localhost";
$dbuser = "root";
$dbpw = "root";
$dbname = "login3";
$tblprefix = "";
$superadmin = "braddmagyar";
$saemail = "braddmagyar@gmail.com";
$said = uniqid(rand(), false);
$site_name = $_SERVER['SERVER_NAME'];
$sapw = password_hash("blahblah", PASSWORD_DEFAULT); */

?>
<!DOCTYPE html>
<html>
<head>
  <title>Progress Bar</title>
  <script src="http://code.jquery.com/jquery-3.1.0.min.js"></script>
  <link rel="stylesheet" type="text/css" href="../login/css/main.css">
    <link rel="stylesheet" type="text/css" href="bootstrap/bootstrap.css">
    <script src="bootstrap/bootstrap.js"></script>
</head>
<body>

<div class="progressdiv">
  <div id="progress">
  <div class="bar" style="width:0"></div>
  </div>
  <div id="message"></div>
</div>
  <script>
    var timer;

    function refreshProgress() {

      $.ajax({
        url: "instchecker.php?file=<?php
echo session_id();
?>",
        success:function(data){

          $(".bar").animate({ width: data.percent + "%"});

          $("#message").html(data.message);

          if (data.percent >= 100 && data.failure == 0) {

            window.clearInterval(timer);

          }
          else if (data.failure == 1) {

            $(".bar").css({"background-color": "#cc0000"});
            window.clearInterval(timer);

          }
        }
      });
    }

    function completed() {
      window.clearInterval(timer);
    }

    $(document).ready(function(){

        $.ajax({
            method: "POST",
            url: "instprocess.php",
            data: { dbhost: "<?php echo $dbhost;?>", dbuser: "<?php echo $dbuser;?>", dbpw: "<?php echo $dbpw;?>", dbname: "<?php echo $dbname;?>", tblprefix: "<?php echo $tblprefix;?>", superadmin: "<?php echo $superadmin;?>", saemail: "<?php echo $saemail;?>", said: "<?php echo $said;?>", sapw: "<?php echo $sapw;?>", base_dir: "<?php echo $base_dir;?>", base_url: "<?php echo $base_url;?>", site_name: "<?php echo $site_name;?>" }
        });

        timer = window.setInterval(refreshProgress, 500);

    });
  </script>
</body>
</html>
