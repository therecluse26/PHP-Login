<?php
class PasswordPolicy {
    public static function validate ($pw1, $pw2, $policy_enforce, $minlength) {

        try {
            $resp = array();

            if ($policy_enforce == true) {

                if($pw1 == $pw2){

                    if (strlen($pw1) >= $minlength && preg_match('/[A-Z]/', $pw1) > 0 && preg_match('/[a-z]/', $pw1) > 0) {

                        //Password policy success
                        $resp['status'] = true;
                        $resp['message'] = '';
                        return $resp;

                    } else {

                        $resp['status'] = false;
                        throw new Exception("<div class='alert alert-danger alert-dismissable'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>Password must include at least one upper and lower case letter and be at least {$minlength} characters long</div><div id='returnVal' style='display:none;'>false</div>");
                    }

                } else {
                    $resp['status'] = false;
                    throw new Exception("<div class='alert alert-danger alert-dismissable'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>Password fields must match</div><div id='returnVal' style='display:none;'>false</div>");
                }

            } else {

                if($pw1 == $pw2){
                    //No password policy success
                    $resp['status'] = true;
                    $resp['message'] = '';
                    return $resp;
                }
            }

        } catch (Exception $e) {
            //Password validation failed
            $resp['status'] = false;
            $resp['message'] = $e->getMessage();
            return $resp;
        }
    }
}
