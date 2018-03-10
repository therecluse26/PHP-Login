<?php
$title = "The very fancy Page 3";
$pagetype = "userpage"; // Allow only logged in users
include "top_menu.php";
include "login/partials/pagehead.php";
?>
</head>
<body>
    <div class="container">

        <h2>Page 3</h2>
        <p>Still here <?=$_SESSION["username"]?>?</p>

    </div>
</body>
</html>
