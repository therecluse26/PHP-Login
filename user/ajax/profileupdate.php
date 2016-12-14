<?php
if(!isset($_SESSION))
{
    session_start();
}
require '../../login/autoload.php';

$conf = new GlobalConf;
$uid = $_SESSION['uid'];
$form = $_POST;


//trigger_error(print_r(form, true));

//if($_FILES["userimage"]["error"] == 0){

    //if($_FILES["userimage"]["size"] < 500000) {
    if(array_key_exists('userimage', $form)){

        $extension = 'jpg';

        $imgtarget = $conf->base_dir.$conf->avatar_dir."/".$uid .".". $extension;

        $imgurl = $conf->base_url.$conf->avatar_dir."/".$uid .".". $extension;

        $form['userimage'] = $imgurl;

            try{

                $upsert = profileData::upsertUserInfo($uid, $form);

                if($upsert == 1 && array_key_exists('userimage', $form)) {

                    $imgresp = ImgHandler::putImage($imgtarget, $_POST['userimage']);

                    echo $imgresp;

                } else {
                    throw new Exception("<div class=\"alert alert-danger alert-dismissable\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>Database/image update failed</div>");
                }

            } catch (Exception $e) {
                echo $e->getMessage();
                die();
            }

        } else {

            try{

                $upsert = profileData::upsertUserInfo($uid, $form);

                if($upsert == 1) {

                    echo $upsert;

                } else {
                    throw new Exception("<div class=\"alert alert-danger alert-dismissable\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>Database update failed</div>");
                }

            } catch (Exception $e) {
                echo $e->getMessage();
                die();
            }


    };


   /* } else {

        echo "<div class=\"alert alert-danger alert-dismissable\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>Image must be smaller than 500kB</div>";

    }

 } else {

    try {
        $upsert = profileData::upsertUserInfo($uid, $form);
        echo $upsert;
    } catch (Exception $e) {
        echo $e->getMessage();
        die();
    }
} */
