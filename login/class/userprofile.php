<?php 
class userProfile extends DbConn
{

    public function pullUserField($uid, $field) {

        //Pull specific user data
        $db = new DbConn;
        $tbl_memberinfo = $vdb->tbl_memberinfo;

        // prepare sql and bind parameters
        $stmt = $db->conn->prepare("SELECT ".$field." from ".$tbl_memberinfo." WHERE userid = :userid");
        $stmt->bindParam(':userid', $uid);

    }

    public function pullUserInfo($uid) {

        //Pull user info into edit form
        $db = new DbConn;
        $tbl_memberinfo = $vdb->tbl_memberinfo;

        // prepare sql and bind parameters
        $stmt = $db->conn->prepare("SELECT firstname, lastname, phone, address1, address2, city, state, country, bio, userimage from ".$tbl_memberinfo." WHERE userid = :userid");
        $stmt->bindParam(':userid', $uid);

    }

    public function upsertUserInfo($uid) {

        //Upsert user data

    }

}