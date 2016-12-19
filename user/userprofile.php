<?php
require '../login/partials/pagehead.php';
?>
</head>
<body>
<div class="container">
<?php
if (isset($_SESSION)) {

    $uid = $_SESSION['uid'];

    $usr = profileData::pullAllUserInfo($uid);

    $acct = UserData::pullUserById($uid);

    foreach ($acct as $key => $value) {
        echo "<p>".ucfirst($key).": $value</p>";
    }

    foreach ($usr as $key => $value) {
        if ($key == 'userimage') {
            echo "<p><img width='300px' height='300px' src='$value'></img>";
        } else {
            echo "<p>".ucfirst($key).": $value</p>";
        }
    }

} else {

    header("location:../login/index.php");

}
?>
</div>
</body>
</html>
