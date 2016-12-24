<?php
class AppConfig extends DbConn
{
    public function __construct() {

        parent::__construct();

        $sql = "SELECT setting, value FROM ".$this->tbl_appConfig;
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $settings = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

        //Pulls all properties from database
        foreach ($settings as $key => $value) {

            $this->{$key} = $value;
        }

    }

    public static function pullSetting($setting, $type = 'varchar')
    {
        $db = new DbConn;
        try {
        if ($type === 'varchar') {
            $sql = "SELECT value FROM ".$db->tbl_appConfig." WHERE setting = :setting LIMIT 1";
        } else {
            $sql = "SELECT CAST(value AS ".$type.") FROM ".$db->tbl_appConfig." WHERE setting = :setting LIMIT 1";
        }
            $stmt = $db->conn->prepare($sql);
            $stmt->bindParam(':setting', $setting);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_NUM);

        } catch (PDOException $e) {

            $result[0] = "Error: " . $e->getMessage();
        }
        return $result[0];
    }

    public static function pullMultiSettings($settingArray)
    {
        $db = new DbConn;

        try {

            $in = str_repeat('?,', count($settingArray) - 1) . '?';

            $sql = "SELECT setting, value FROM ".$db->tbl_appConfig." WHERE setting IN ($in)";

            $stmt = $db->conn->prepare($sql);
            $stmt->execute($settingArray);
            $result = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

        } catch (PDOException $e) {

            $result = "Error: " . $e->getMessage();
        }

        return $result;
    }

    public static function pullAllSettings()
    {
        if ($_SESSION["admin"] == 1) {

            $db = new DbConn;

            try {
            $sql = "SELECT setting, value, description, type, category FROM ".$db->tbl_appConfig." where type != 'hidden' order by -sortorder desc";

            $stmt = $db->conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_NUM);

            } catch (PDOException $e) {

                $result = "Error: " . $e->getMessage();
            }

        } else {
            $result = "You must be an admin to use this method";
        }

        return $result;
    }

    public function updateMultiSettings($settingArray)
    {
        try {
            $db = new DbConn;

            foreach ($settingArray as $setting=>$value) {

                try {

                    $sql = "UPDATE ".$db->tbl_appConfig." SET value = :value WHERE setting = :setting";

                    $stmt = $db->conn->prepare($sql);
                    $stmt->bindParam(":value", $value);
                    $stmt->bindParam(":setting", $setting);
                    $stmt->execute();

                } catch (PDOException $e) {
                    $result['status'] = false;
                    $result['message'] = "Error: " . $e->getMessage();
                }

            }

            $result['status'] = true;
            $result['message'] = "<div class=\"alert alert-success alert-dismissable\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>Changes Saved Successfully</div>";

        } catch (Exception $x) {
            $result['status'] = "<div class=\"alert alert-danger alert-dismissable\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>Your account has been created, but you cannot log in until it has been verified</div>";
            $result['message'] = $x->getMessage();

        }

        return $result;
    }

}
