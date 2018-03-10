<?php
class MiscFunctions
{
    public static function mySqlErrors($response)
    {
        //Returns custom error messages instead of MySQL errors

        switch (substr($response, 0, 22)) {

            case 'Error: SQLSTATE[23000]':
                echo "<div class=\"alert alert-danger alert-dismissable\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>Username or email already exists</div>";
                break;

            default:
                echo $response."<div class=\"alert alert-danger alert-dismissable\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>An error occurred... try again</div>";

        }

    }

    public static function assembleUids($uid_string) {

        $uid_array = json_decode($uid_string);

        foreach ($uid_array as $id) {
            if (isset($uids)) {
                $uids = $uids.", '".$id."'";
            } else {
                $uids = "'".$id."'";
            };
        };

        return $uids;
    }

    // Check if url is relative or absolute
    public static function isAbsUrl($url) {
        if ($url[0] == '/') {
            return true;
        }
        
        if (strpos($url, "://")) {
            return true;
        }
        
        return false;
    }
}
