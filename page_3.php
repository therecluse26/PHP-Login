<?php

// Example of overriding the menu in phplogin_menu.php
$barmenu = array(
    "Index Page" => "index.php"
); 

$title = "The very fancy Page 3";
$pagetype = "userpage"; // Allow only logged in users
include "login/misc/pagehead.php";
?>
</head>
<body>
    <div class="container">

        <h2>Page 3</h2>
        <p>Still here <?=$_SESSION["username"]?>?</p>

    </div>
</body>
</html>
