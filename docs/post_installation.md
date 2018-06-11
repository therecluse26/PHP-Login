Post Installation
-----------------

Now that basic installation is completed, we will need to login and do some simple site configuration. Navigate to the root of your site to login under the superadmin account you just created: ex: `[your_site]/login/index.php`

 <img src="/docs/images/loginfrm_empty.png" alt="Log in" style="display:block;margin:auto;" height="300px" />

Once you are signed in for the first time under your superadmin account, we need to finish editing site configuration. Click on the top right corner of your screen where your username is located and select `Edit Site Config` to continue.

 <img src="/docs/images/dropdown_menu.png" alt="Edit Site Config" style="display:block;margin:auto;" height="200px" />

On the `Edit Site Configuration` page, numerous configuration options can be set. Be aware, that some of these changes (such as `base_url`) can lead to a broken site if configured incorrectly. If any of these config changes do lead to a non-functioning site, you can recover it by updating the `app_config` MySQL database table to the correct values.

For baseline functionality, ensure that proper SMTP settings are configured in the `Mailer` tab. Once this is filled out, click `Save` and then `Test Email Config` to show if a successful email connection was made or otherwise show connection/authentication errors.

 <img src="/docs/images/superadmin_email_config.png" alt="Mail Config" style="display:block;margin:auto;" height="400px" />


*_For quick reference, hover over the name of each setting to see a description of what it does_


Verify that everything is working properly. Once this is done **remember to delete the `/install` directory**

#### Congratulations, you've successfully set up PHP-Login! Now get to building your website!

To learn about additional features, open the corner dropdown menu and explore the options contained.

**Note:** The available options will be different if a user is an admin vs a standard user. Standard users will only see the `Edit Profile`, `Account Settings` and `Logout` menu options.
