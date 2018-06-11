- appconfig.php
  - construct
  - pullSetting
  - pullMultiSettings
  - pullAllSettings
  - updateMultiSettings

- cookiehandler.php
  - generateCookie
  - validateCookie

- emaillogger.php
  - getUnreadLogs
  - deleteLog
  - logResponse

- loginhandler.php
  - checkLogin
  - checkAttempts
  - insertAttempt
  - updateAttempts

- newuser.php
  - createUser

- passwordform.php
  - resetPw

- ProfileData.php
  - pullUserFields
  - pullAllUserInfo
  - upsertUserInfo

- RoleHandler.php
  - checkRole
  - getDefaultRole
  - listAllRoles
  - listRoleUsers
  - listUserRoles
  - assignRole
  - unassignRole
  - unassignAllRoles

- TokenHandler.php
  - selectToken
  - replaceToken

- UserData.php
  - userDataPull
  - pullUserPassword
  - upsertAccountInfo

- UserHandler.php
  - pullUserByEmail
  - pullUserById
  - getUserVerifyList
  - getActiveUsers
  - getUnverifiedUsers
  - adminEmailList
  - deleteUser
  - banUser
  - verifyUser
