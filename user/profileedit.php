<?php
$title = 'Edit Profile';
require '../login/partials/pagehead.php';
$uid = $_SESSION['uid'];
$usr = profileData::pullAllUserInfo($uid);
$imgpath = $usr['userimage'];
?>
    </head>
    <body>
        <div class="container">
            <div class="col-sm-2"></div>
            <div class="col-sm-8">

                <h2><?php echo $title;?></h2>

                <form id="profileForm" enctype="multipart/form-data">

                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-6">
                                <label class="label label-default">Select Your Image</label>
                                <br/>
                                <input type="file" name="userimage" id="userimage" accept="image/*" />
                                <div id="imgholder">
                                    <img id="imgthumb" class="img-thumbnail" src="<?php echo $imgpath;?>" />
                                </div>
                                <input id="base64image" hidden></input>
                                <br/>

                            </div>

                        <div class="col-sm-6">

                             <label for="firstname" class="label label-default">First Name</label>
                                <input type="text" class="form-control" name="firstname" id="firstname" value="<?php echo $usr['firstname']; ?>">

                            <label for="lastname" class="label label-default">Last Name</label>
                                <input type="text" class="form-control" name="lastname" id="lastname" value="<?php echo $usr['lastname']; ?>">

                                <label for="address1" class="label label-default">Address 1</label>
                                <input type="text" class="form-control" name="address1" id="address1" value="<?php echo $usr['address1']; ?>">

                                <label for="address2" class="label label-default">Address 2</label>
                                <input type="text" class="form-control" name="address2" id="address2" value="<?php echo $usr['address2']; ?>">

                                <label for="city" class="label label-default">City</label>
                                <input type="text" class="form-control" name="city" id="city" value="<?php echo $usr['city']; ?>">

                                <label for="state" class="label label-default">State</label>
                                <input type="text" class="form-control" name="state" id="state" value="<?php echo $usr['state']; ?>">
                        </div>


                        </div>
                    </div>



                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-6">
                                <label for="phone" class="label label-default">Phone</label>
                                <input type="text" class="form-control" name="phone" id="phone" value="<?php echo $usr['phone']; ?>">
                            </div>
                            <div class="col-sm-6">
                                <label for="Country" class="label label-default">Country</label>
                                <input type="text" class="form-control" name="country" id="country" value="<?php echo $usr['country']; ?>">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-12">
                                <label for="bio" class="label label-default">Bio</label>
                                <textarea rows="6" name="bio" class="form-control" id="bio"><?php echo $usr['bio']; ?></textarea>
                            </div>
                        </div>
                    </div>

                    <br/>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-12">
                                <button type="submit" class="btn btn-primary" id="submitbtn">Submit</button>
                                <span id="message"></span>
                            </div>
                        </div>
                </form>
                </div>
            </div>
            <div class="col-sm-2"></div>
        </div>
    </body>
    </html>
