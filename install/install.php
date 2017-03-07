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

?>
<!DOCTYPE html>
<html>
<head>
  <title>Installing...</title>
  <script src="js/jquery.min.js"></script>
  <link rel="stylesheet" type="text/css" href="../login/css/main.css">
    <link rel="stylesheet" type="text/css" href="bootstrap/bootstrap.css">
    <script src="bootstrap/bootstrap.js"></script>
</head>
<body>

<div class="progressdiv">
  <div id="progress">
  <div class="bar" style="width:0"></div>
  </div>
  <div id="instmessage" style="overflow-y:scroll;"></div>
</div>
  <script>
    var timer;
    var msg1;
    var msg2;
    function refreshProgress() {

      $.ajax({
        url: "instchecker.php?file=<?php echo session_id();?>",
        success:function(data){

          $(".bar").animate({ width: data.percent + "%"});

          msg1 = data.message;

          if (msg2 == msg1) {
            msg2 = data.message;

          } else {

            $("#instmessage").append(data.message + "<br>");
            $("#instmessage").css("height", "100px");
            $('#instmessage').animate({scrollTop: $('#instmessage').prop("scrollHeight")}, 500);
            msg2 = data.message;
          }

          console.log(JSON.stringify(data));

          if (data.percent >= 100 && data.failure == 0) {

            window.clearInterval(timer);
            $(".bar").animate({ width: "100%"});
            document.title = "Installation Complete!";

          }
          else if (data.failure == 1) {

            $(".bar").css({"background-color": "#cc0000"});
            window.clearInterval(timer);
            document.title = "Installation Failed :(";

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
            data: { dbhost: "<?php echo $dbhost;?>", dbuser: "<?php echo $dbuser;?>", dbpw: "<?php echo $dbpw;?>", dbname: "<?php echo $dbname;?>", tblprefix: "<?php echo $tblprefix;?>", superadmin: "<?php echo $superadmin;?>", saemail: "<?php echo $saemail;?>", said: "<?php echo $said;?>", sapw: "<?php echo $sapw;?>", base_dir: "<?php echo $base_dir;?>", base_url: "<?php echo $base_url;?>", site_name: "<?php echo $site_name;?>" },
            success: function(html) {

                $("#instmessage").append(html);

            }
        });

        timer = window.setInterval(refreshProgress, 250);

    });
  </script>
</body>
</html>
