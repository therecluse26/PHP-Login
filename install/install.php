<?php
// Start the session.
session_start();

$dbhost = $_POST['dbhost'];
$dbuser = $_POST['dbuser'];
$dbpw = $_POST['dbpw'];
$dbname = $_POST['dbname'];
$tblprefix = $_POST['tblprefix'];
$superadmin = $_POST['superadmin'];
$saemail = $_POST['saemail'];
$said = uniqid(rand(), false);
$sapw = password_hash($_POST['sapw'], PASSWORD_DEFAULT);
$site_name = $_SERVER['SERVER_NAME'];
$sapw = password_hash($_POST['sapw'], PASSWORD_DEFAULT);
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
        data: { dbhost: "<?php echo $dbhost;?>", dbuser: "<?php echo $dbuser;?>", dbpw: "<?php echo $dbpw;?>", dbname: "<?php echo $dbname;?>", tblprefix: "<?php echo $tblprefix;?>", superadmin: "<?php echo $superadmin;?>", saemail: "<?php echo $saemail;?>", said: "<?php echo $said;?>", sapw: "<?php echo $sapw;?>" }
        });

      timer = window.setInterval(refreshProgress, 500);
    });
  </script>
</body>
</html>
