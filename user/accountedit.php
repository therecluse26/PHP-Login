<?php
$title = 'Account Settings';
require '../login/partials/pagehead.php';
$uid = $_SESSION['uid'];
$usr = UserData::pullUserById($uid);
?>
</head>
<body>
    <div class="container">
        <div class="col-sm-4"></div>
        <div class="col-sm-4">

            <h2><?php echo $title;?></h2>

            <form id="profileForm" enctype="multipart/form-data">

                <div class="form-group">
                    <div class="row">


                    <div class="col-sm-12">

                         <label for="username" class="label label-default">Username</label>
                            <input type="text" class="form-control" name="username" id="username" value="<?php echo $usr['username']; ?>" disabled>

                        <label for="email" class="label label-default">Email</label>

                        <input type="text" class="form-control" name="email" id="email" value="<?php echo $usr['email']; ?>">

                    </div>
                    </div>
                </div>
                <br/>
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-12">
                            <div id="message"></div>

                            <button type="submit" class="btn btn-lg btn-primary btn-block" id="submitbtn">Submit</button>

                        </div>
                    </div>
            </form>
            </div>
            <input id="emailorig" value="<?php echo $usr['email']; ?>" hidden disabled></input>
        </div>
        <div class="col-sm-4"></div>
    </div>
</body>
</html>
