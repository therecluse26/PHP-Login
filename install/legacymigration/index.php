<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Migrate PHP-Login from legacy versions</title>
    <meta name="Author" content="" />
    <link rel="stylesheet" type="text/css" href="../bootstrap/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="../../login/css/main.css">
    <script src="../js/jquery.min.js"></script>
    <script src="../js/jquery.validate.min.js"></script>
    <script src="../bootstrap/bootstrap.js"></script>
    <script src="../ajax/instvalidate.js"></script>
    <script src="../js/modalInstallSelect.js"></script>
</head>

<body>
    <div class="container">
        <table class="table table-bordered table-striped table-highlight">

            <h1>Migrate PHP-Login database</h1>

            <form id="dbform" action="migrate.php" class="form-signin" method="post">

                <div class="form-group row">
                    <div class="col-sm-6">
                        <label for="dbhost" id="dbhost">Old DB Hostname</label>
                        <input name="dbhost" id="dbhost" class="form-control" placeholder="Database Hostname" required></input>
                        <br>
                    </div>
                    <div class="col-sm-6">
                        <label for="dbuser" id="dbuser">Old DB User</label>
                        <input name="dbuser" id="dbuser" class="form-control" placeholder="Username" required></input>
                        <br>
                    </div>
                    <div class="col-sm-6">
                        <label for="dbpw" id="dbpw">Old DB Password</label>
                        <input name="dbpw" id="dbpw" class="form-control" placeholder="Password"></input>
                        <br>
                    </div>
                    <div class="col-sm-6">
                        <label for="dbname" id="dbname">Old DB Name</label>
                        <input name="dbname" id="dbname" class="form-control" placeholder="Database Name" required></input>
                        <br>
                    </div>
                    <div class="col-sm-6">
                        <label for="tblprefix" id="tblprefix">Old DB Table Prefix</label>
                        <input name="tblprefix" id="tblprefix" class="form-control" placeholder="Table prefix"></input>
                        <br>
                    </div>

                </div>


                <div class="form-group row">
          <div class="col-sm-6">
              <label for="newdbhost" id="newdbhost">New DB Hostname</label>
              <input name="newdbhost" id="newdbhost" class="form-control" placeholder="New Hostname" required></input>
              <br>
          </div>
          <div class="col-sm-6">
              <label for="newdbuser" id="newdbuser">New DB User</label>
              <input name="newdbuser" id="newdbuser" class="form-control" placeholder="Username" required></input>
              <br>
          </div>
          <div class="col-sm-6">
              <label for="newdbpw" id="newdbpw">New DB Password</label>
              <input name="newdbpw" id="newdbpw" class="form-control" placeholder="Password"></input>
              <br>
          </div>
          <div class="col-sm-6">
              <label for="newdbname" id="newdbname">New DB Name</label>
              <input name="newdbname" id="newdbname" class="form-control" placeholder="Database Name" required></input>
              <br>
          </div>
          <div class="col-sm-6">
              <label for="newtblprefix" id="newtblprefix">New DB Table Prefix</label>
              <input name="newtblprefix" id="newtblprefix" class="form-control" placeholder="Table prefix"></input>
              <br>
          </div>

      </div>

    <div id="respmsg">
        <input id="submitbtn" type="submit" class="btn btn-primary"></input>
    </div>

    </form>

</div>

<script>
  $("#dbform").submit(function(e){
    e.preventDefault();

    var getUrl = window.location;
    var baseUrl = getUrl .protocol + "//" + getUrl.host + "/" + getUrl.pathname.split('/')[1];


    $.ajax({
      url: this.action,
      method: "POST",
      data: $(this).serialize(),
      beforeSend: function(){
        console.log("beforeSend");
      },
      success: function(resp){
        $("#respmsg").html(resp + " <a href='"+baseUrl+"/login/index.php'>Click here to login</a>");
      },
      error: function(err){
        alert(err.responseText);
        console.log(err);
      }
    });
  })
</script>

</body>
</html>
