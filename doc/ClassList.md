- appconfig.php
  - construct
  - pullSetting
  - pullMultiSettings
  - pullAllSettings
  - updateMultiSettings

- cookiehandler.php
  - generateCookie
  - validateCookie


- mailhandler.php
  - configure
  - sendMail
  - sendResetMail
  - getUnreadLogs
  - deleteLog
  - logResponse
  - testMailSettings


- loginhandler.php
  - checkLogin (multiple)
  - checkAttempts
  - insertAttempt
  - updateAttempts

- passwordhandler.php
  - encryptPw
  - resetPw
  - validatePolicy

- profiledata.php (consolidate to UserData.php class)
  - pullUserFields
  - pullAllUserInfo
  - upsertUserInfo

- rolehandler.php
  - checkRole
  - getDefaultRole
  - listAllRoles
  - listRoleUsers
  - listUserRoles
  - assignRole
  - unassignRole
  - unassignAllRoles

- tokenhandler.php
  - selectToken
  - replaceToken

- userdata.php (merge profiledata.php into this)
  - userDataPull
  - pullUserPassword
  - upsertAccountInfo

- userhandler.php (consolidate?)
  - pullUserByEmail
  - pullUserById
  - createUser
  - deleteUser
  - banUser
  - verifyUser

- adminfunctions.php
  - getUserVerifyList
  - getActiveUsers
  - getUnverifiedUsers
  - adminEmailList
