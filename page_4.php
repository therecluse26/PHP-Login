<?php

$title = "The very fancy Page 4";
$pagetype = "adminpage"; // Allow only logged in administrators
include "login/misc/pagehead.php";
?>
</head>
<body>
    <div class="container">

        <h2>Page 4</h2>
        <p>Still here <?=$_SESSION["username"]?>?</p>

    </div>
</body>
</html>
