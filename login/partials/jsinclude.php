<?php 
switch ($page) {
    case "resetpw":
        echo '<script src="'.$url.'/login/vendor/components/jquery/jquery.min.js"></script><script src="'.$url.'/login/vendor/components/bootstrap/js/bootstrap.js"></script>';
        echo '<script src="'.$url.'/login/js/resetpw.js"></script>';
        echo '<script src="http://jqueryvalidation.org/files/dist/jquery.validate.min.js"></script>';
        echo '<script src="http://jqueryvalidation.org/files/dist/additional-methods.min.js"></script>';
        break;

    case "signup":
        echo '<script src="'.$url.'/login/vendor/components/jquery/jquery.min.js"></script><script src="'.$url.'/login/vendor/components/bootstrap/js/bootstrap.js"></script>';
        echo '<script src="'.$url.'/login/js/signup.js"></script>';
        echo '<script src="http://jqueryvalidation.org/files/dist/jquery.validate.min.js"></script>';
        echo '<script src="http://jqueryvalidation.org/files/dist/additional-methods.min.js"></script>';
        break;

    case "adminverify":
        echo '<script src="'.$url.'/login/vendor/components/jquery/jquery.min.js"></script><script src="'.$url.'/login/vendor/components/bootstrap/js/bootstrap.js"></script>';
        echo '<script src="'.$url.'/login/admin/js/adminverify.js"></script>';
        echo '<script src="/login/admin/js/admindelete.js"></script>';
        echo '<script src="/login/admin/js/adminselectall.js"></script>';
        break;

    default:
        echo '<script src="'.$url.'/login/vendor/components/jquery/jquery.min.js"></script><script src="'.$url.'/login/vendor/components/bootstrap/js/bootstrap.js"></script>';
        break;

}