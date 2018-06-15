API Methods
-----------

**In the site header *(`/login/misc/pagehead.php`)*, two objects are spawned:**

  1) An authentication object named `$auth` that can be used as an accessor to various utility methods dealing with user authentication:
  **`$auth = new PHPLogin\AuthorizationHandler;`**

  These utility methods are:

  - **`hasRole($roleName)`**
    - Allows checking a role against the current logged-in user.
      - Returns boolean `true` or `false` value.
    - Method called like: `$auth->hasRole('Moderator')`
    - Roles and user/role bindings can be handled in the `/admin/roles.php` page by a `Superadmin` or by another role with the relevant permissions

  - **`hasPermission($permissionName)`**
    - Allows checking a permission against the current logged-in user.
      - Returns boolean `true` or `false` value.
    - Method called like: `$auth->hasPermission('Edit Posts')`
    - Permissions and role/permission bindings can be handled in the `/admin/permissions.php` page by a `Superadmin` or by another role with the relevant permissions

  - **`isAdmin()`**
    - Simple helper function to check if current user has the `Admin` role assigned to them.
      - Returns boolean `true` or `false` value.
    - Method called like: `$auth->isAdmin`;

  - **`isSuperAdmin()`**
    - Simple helper function to check if current user has the `Superadmin` role assigned to them.
      - Returns boolean `true` or `false` value.
    - Method called like: `$auth->isSuperAdmin`;

  - **`checkSessionKey($key)`**
    - Checks `$_SESSION` superglobal object for a specified key.
      - Returns session value of given key if found, or `false` if not
    - Method called like `$auth->checkSessionKey`

  2) A configuration object named `$conf` that can be used to access configuration variables from *`app_config`* database table: **`$conf = new PHPLogin\PageConstructor($auth); (This extends the AppConfig class)`**

  This object automatically pulls all values from `app_config` database table as object properties that can be accessed like **`$conf->property_name`**

#### Other Useful Functions
  Two useful (static) methods are available in the `AppConfig` class
  - **`pullSetting($setting)`**
    - Returns value from given *`$setting`*
    - Calls can be made like: `$setting = PHPLogin\AppConfig::pullSetting("settingName1")`
  - **`pullMultiSettings($settingArray)`**
    - Returns array of values from given *`$settingArray`*
    - Calls can be made like: `$settings = PHPLogin\AppConfig::pullMultiSettings(array("settingName1", "settingName2", "etc"))`

\* *Full API documentation can be found by nagivating to:* `{yoursite.com}/docs/api/index.html`
