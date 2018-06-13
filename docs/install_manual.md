Manual Installation
-------------------

The manual installation process is, well, more manual than the automated installer. A simple tool, however, has been provided to save time and headache in generating the necessary sql scripts, config files and providing some additional guidance.

Fill out all requested information on this page and click the `Generate Configuration` button on the bottom.

 <img src="/docs/images/maninstall_empty.png" alt="Manual Install Form" style="display:block;margin:auto;" height="500px" />

Configuration and SQL scripts will be generated. Several manual steps will now be necessary.

- Copy/Paste and run the SQL script on the desired database server as a user with admin privileges. This script will generate the database/tables as well as all required triggers, indexes, etc.

- Copy/paste the configuration output into the `/login/dbconf.php` file.

- Open a terminal and navigate to the site root directory that includes PHP-Login and run `composer install --no-dev` to pull in required depenencies.

 <img src="/docs/images/maninstall_filled.png" alt="Manual Install Generated Code" style="display:block;margin:auto;" height="500px" />

Installation is now complete, however, a few steps are still necessary before your site is functional. Proceed to [Post Installation Instructions](post_installation.md)
