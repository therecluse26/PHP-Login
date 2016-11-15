<?php
session_start();

if (!array_key_exists('admin', $_SESSION)  || $_SESSION['ip_address'] != getenv ( "REMOTE_ADDR" )) {
    session_destroy();
    header("location:../index.php");
} else {
  
chdir('../');
$page = 'adminverify';
$title = 'Admin Verification';
require 'autoload.php';
require 'class/functions.php';
include 'config.php';
include $base_dir. "/login/partials/pagehead.php";

//include 'partials/adminpagehead.php';
$users = AdminUserPull::UserList();
$x = 0;
?>

< <a href="/login/logout.php">Logout</a>
<div class="userdiv">

<?php
    if (!empty($users)) {

        echo '<table id="userlist" class="userlist"><thead><th>Username</th><th>Email</th><th></th><th></th><th>Select All</th></thead><tr class="thAll"><td></td><td></td><td></td><td></td><td><input type="checkbox" id="selectAll"></input></td></tr>';

        foreach($users as $user){
            $x++;
            echo '<tr class="tableRow" id="row'.$x.'"><td><input name="userid" id="userid'.$x.'" value="'.$user['id'].'" class="readonlyinput" hidden readonly></input><input name="username" id="username'.$x.'" value="'.$user['username'].'" class="readonlyinput" readonly></input></td><td><input id="email'.$x.'" value="'.$user['email'].'" class="readonlyinput" readonly></input></td><td><button id="verbutton'.$x.'" class="verbutton" onclick="verifyUser(\''.$user['id'].'\',\''.$user['email'].'\',\''.$user['username'].'\',\''.$x.'\');">Verify</button></td><td><button id="delbutton'.$x.'" class="delbutton" onclick="deleteUser(\''.$user['id'].'\',\''.$user['email'].'\',\''.$user['username'].'\',\''.$x.'\');">Delete</button></td><td><input type="checkbox" value="'.$user['id'].'" id="'.$x.'"></input></td></tr>';
        }
        echo '</table><button id="verAll" class="verbutton" onclick="verifyAll();">Verify All</button>';
    } else {
        echo '<p class="message">No new users!</p>';
    };

};

?>
    </table>
    </div>
    </form>
    </body>
</html>