API Methods
-----------

**In the site header *(`/login/misc/pagehead.php`)*, two objects are spawned:**

  1) An authentication object named `$auth` that can be used as an accessor to various utility methods dealing with user authentication:
  **`$auth = new PHPLogin\AuthorizationHandler;`**



  2) A configuration object named `$conf` that can be used to access configuration variables from *`app_config`* database table: **`$conf = new PHPLogin\PageConstructor($auth); // This extends the AppConfig class`**

  This object automatically pulls all values from `app_config` database table as object properties that can be accessed like **`$conf->property_name`**

  Several useful related static methods are available in the `AppConfig` class
  - **`pullSetting($setting, $type = 'varchar')`**
    - Returns value from given *`$setting`*
    - Calls can be made like: `PHPLogin\AppConfig::pullSetting("setting1")`
  - **`pullMultiSettings($settingArray)`**
    - Returns array of values from given *`$settingArray`*
    - Calls can be made like so: `PHPLogin\AppConfig::pullMultiSettings(array("setting1", "setting2", "etc"))`
