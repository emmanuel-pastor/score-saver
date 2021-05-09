<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <link href=<?php echo BASE_PATH."css/template.css"?> rel="stylesheet"/>
    <title><?php echo $title; ?></title>
</head>
<body>
<div id="bandeau">bandeau...</div>
<div id="auth">
    <?php
        if (!isset($_SESSION['email'])) {
            echo "<a href=\"".BASE_PATH."authentication/login\">Login</a>" . " - <a href=\"".BASE_PATH."user/create\">Sign Up</a>";
        } else {
            echo $_SESSION['email'] . " - <a href=\"".BASE_PATH."authentication/logout\">Logout</a>";
        }
    ?>
</div>
<div id="menu">menu</div>
<div id="corps"><?php echo $corps; ?></div>
<footer>MOOC AppDyn</footer>

</body>
</html>
