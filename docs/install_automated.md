Automated Installation
----------------------

### Clone the Repository
    $ git clone https://github.com/therecluse26/PHP-Login.git

### Run through web-based installer
Open this link in your web browser (replacing [yoursite.com] with your site address)

    http://[yoursite.com]/install/index.php

Select an installation option from the pop-up modal that appears: `Automated` or `Manual`

<div id="autoinstall">
<h3>Automated Install</h3>
<hr>
</div>

Enter all relevant information into the form, submit, and wait for install to complete.

<img src="https://raw.githubusercontent.com/therecluse26/PHP-Login/master/doc/images/auto_install_form.png" alt="Automated Installer Form" style="display:block;margin:auto;" height="400px" />

This will generate necessary database connection and configuration files, pull required `Composer` dependencies, and create/seed the database with user supplied data.

<img src="https://raw.githubusercontent.com/therecluse26/PHP-Login/master/doc/images/auto_install_inprogress.png" alt="Automated Installer In Progress" style="display:block;margin:auto;" height="300px" />

##### If any errors occur, or if you for some reason feel like being awesome, you may install manually. See the secton entitled [Manual Installation Instructions](install_manual.md) for more information.

 <img src="https://raw.githubusercontent.com/therecluse26/PHP-Login/master/doc/images/auto_install_success.png" alt="Automated Installer Complete" style="display:block;margin:auto;" height="300px" />

Continue to [Post Installation Instructions](post_installation.md)
