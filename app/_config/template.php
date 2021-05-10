<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link href=<?php echo BASE_PATH . "css/template.css" ?> rel="stylesheet"/>
    <title><?php echo $title ?></title>
</head>
<body>
<nav>
    <h1><a href="<?php echo  BASE_PATH ?>"">Score Saver<a></h1>
    <?php
    if (!isset($_SESSION['email'])) {
        echo "<a class=\"base_button auth_button\" href=\"" . BASE_PATH . "authentication/login\">Se connecter</a>" . " <a class=\"base_button auth_button\" href=\"" . BASE_PATH . "user/create\">S'enregistrer</a>";
    } else {
        echo $_SESSION['email'] . " <a class=\"base_button auth_button\" href=\"" . BASE_PATH . "authentication/logout\">Se déconnecter</a>";
    }
    ?>
</nav>
<br/>
<div id="corps"><?php echo $corps; ?></div>
<footer>MOOC AppDyn</footer>

</body>
</html>
