<?php 
switch ($title) {
    case "Reset Password":
        echo '<script src="'.$url.'/login/vendor/components/jquery/jquery.min.js"></script><script src="'.$url.'/login/vendor/components/bootstrap/js/bootstrap.min.js"></script>';
        echo '<script src="'.$url.'/login/js/resetpw.js"></script>';
        echo '<script src="http://jqueryvalidation.org/files/dist/jquery.validate.min.js"></script>';
        echo '<script src="http://jqueryvalidation.org/files/dist/additional-methods.min.js"></script>';
        break;

    case "Sign Up":
        echo '<script src="'.$url.'/login/vendor/components/jquery/jquery.min.js"></script><script src="'.$url.'/login/vendor/components/bootstrap/js/bootstrap.min.js"></script>';
        echo '<script src="'.$url.'/login/js/signup.js"></script>';
        echo '<script src="http://jqueryvalidation.org/files/dist/jquery.validate.min.js"></script>';
        echo '<script src="http://jqueryvalidation.org/files/dist/additional-methods.min.js"></script>';
        break;

    case "Admin Verification":
        echo '<script src="'.$url.'/login/vendor/components/jquery/jquery.min.js"></script><script src="'.$url.'/login/vendor/components/bootstrap/js/bootstrap.min.js"></script>';
        echo '<script src="'.$url.'/admin/js/adminverify.js"></script>';
        echo '<script src="'.$url.'/admin/js/admindelete.js"></script>';
        echo '<script src="'.$url.'/admin/js/adminselectall.js"></script>';
        echo '<script src="'.$url.'/admin/js/admincheckrow.js"></script>';
        break;

    default:
        echo '<script src="'.$url.'/login/vendor/components/jquery/jquery.min.js"></script><script src="'.$url.'/login/vendor/components/bootstrap/js/bootstrap.min.js"></script>';
        break;

}