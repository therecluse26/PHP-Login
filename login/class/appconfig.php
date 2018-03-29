<?php
/**
* Handles application configuration settings stored in database `app_config` table
**/
class AppConfig extends DbConn
{
    /**
    * Primarily instantiated in `login/misc/pagehead.php`. Meant to be instantiated once to minimize unnecessary database calls.
    * In any page where `pagehead.php` is included, settings can be pulled as such: `$this->setting_name` where `setting_name` corresponds to "setting" entry in `app_config` database table.
    **/
    function __construct() {

        parent::__construct();

        $sql = "SELECT setting, value FROM ".$this->tbl_appConfig;
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $settings = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

        //Pulls all properties from database
        foreach ($settings as $key => $value) {

            $this->{$key} = $value;
        }

        $this->signin_url = $settings['base_url'].'/login';

        if ($this->from_email == '') {
            $this->from_email = $this->admin_email;
        }

    }
    /**
    * Pulls single setting statically from database without invoking new AppConfig object. Meant to be used in pages where `pagehead.php` is not included.
    * Calls can be made like so: AppConfig::pullSetting('setting_name', 'db_var_type')
    *
    * `setting_name` corresponds to "setting" entry in `app_config` table, and `db_var_type` should be desired db type such as `unsigned` for integers, `varchar`, etc.
    **/
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

        unset($db);

        return $result[0];

    }
    /**
    * Pulls multiple settings statically from database without invoking new AppConfig object. Meant to be used in pages where `pagehead.php` is not included.
    * Calls can be made like so: `AppConfig::pullMultiSettings(array("setting1", "setting2", "etc"))`
    *
    * `$settingArray` = array of settings to be pulled from `app_config` table
    **/
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
    /**
    * Pulls all settings statically from database with descriptions, categories, and input types. Meant to be used specifically in `admin/editconfig.php` page.
    * Calls can be made like so: AppConfig::pullAllSettings()
    **/
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
    /**
    * Updates array of settings.
    * Calls can be made like so: $obj->updateMultiSettings(array("setting1"=>"value1", "setting2"=>"value2", "etc"=>"etc"))
    **/
    public function updateMultiSettings($settingArray)
    {
        try {
            foreach ($settingArray as $setting=>$value) {

                try {

                    $sql = "UPDATE ".$this->tbl_appConfig." SET value = :value WHERE setting = :setting";

                    $stmt = $this->conn->prepare($sql);
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
