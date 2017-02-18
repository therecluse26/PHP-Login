<?php
echo '<script src="'.$this->base_url.'/login/vendor/components/jquery/jquery.min.js"></script><script src="'.$this->base_url.'/login/vendor/components/bootstrap/js/bootstrap.min.js"></script><link href="'.$this->base_url.'/login/vendor/components/bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen"><link href="'.$this->base_url.'/login/css/main.css" rel="stylesheet" media="screen">';

switch ($title) {
    case "Reset Password":
        echo '<script src="'.$this->base_url.'/login/vendor/components/jquery/jquery.min.js"></script><script src="'.$this->base_url.'/login/vendor/components/bootstrap/js/bootstrap.min.js"></script>';
        echo '<script src="'.$this->base_url.'/login/js/resetpw.js"></script>';
        echo '<script src="http://jqueryvalidation.org/files/dist/jquery.validate.min.js"></script>';
        echo '<script src="http://jqueryvalidation.org/files/dist/additional-methods.min.js"></script>';
        echo '<link href="'.$this->base_url.'/login/vendor/components/bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen"><link href="'.$this->base_url.'/login/css/main.css" rel="stylesheet" media="screen">';
        break;

    case "Sign Up":
        echo '<script src="'.$this->base_url.'/login/js/signup.js"></script>';
        echo '<script src="http://jqueryvalidation.org/files/dist/jquery.validate.min.js"></script>';
        echo '<script src="http://jqueryvalidation.org/files/dist/additional-methods.min.js"></script>';
        break;

    case "Forgot Password":
        echo '<script src="'.$this->base_url.'/login/js/forgotpassword.js"></script>';
        echo '<script src="http://jqueryvalidation.org/files/dist/jquery.validate.min.js"></script>';
        echo '<script src="http://jqueryvalidation.org/files/dist/additional-methods.min.js"></script>';
        break;

    case "Admin Verification":
        echo '<script src="'.$this->base_url.'/admin/js/adminverifyuser.js"></script>';
        echo '<script src="'.$this->base_url.'/admin/js/admindeleteuser.js"></script>';
        echo '<script src="'.$this->base_url.'/admin/js/adminselectall.js"></script>';
        echo '<script src="'.$this->base_url.'/admin/js/admincheckrow.js"></script>';
        break;

    case "Mail Log":
        echo '<script src="'.$this->base_url.'/admin/js/admindeletelog.js"></script>';
        echo '<script src="'.$this->base_url.'/admin/js/admincheckrow.js"></script>';
        echo '<script src="'.$this->base_url.'/admin/js/forwardback.js"></script>';
        break;

    case "Edit Site Configuration":
        echo '<script src="'.$this->base_url.'/admin/js/editconfig.js"></script>';
        break;

    case "Edit Profile":
        echo '<script src="'.$this->base_url.'/user/js/profileupdate.js"></script>';
        echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.4.0/croppie.min.js"></script>';
        echo '<link href="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.4.0/croppie.min.css" rel="stylesheet" media="screen">';
        break;

    case "Account Settings":
        echo '<script src="'.$this->base_url.'/user/js/accountupdate.js"></script>';
        echo '<script src="http://jqueryvalidation.org/files/dist/jquery.validate.min.js"></script>';
        echo '<script src="http://jqueryvalidation.org/files/dist/additional-methods.min.js"></script>';
        break;

    default:
        break;

}
