<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <link href="../css/gabarit.css" rel="stylesheet"/>
    <title><?php echo $titre; ?></title>
</head>
<body>
<div id="bandeau">bandeau...</div>
<div id="auth">
    <?php
        if (!isset($_SESSION['mail'])) {
            echo "<a href=\"../authentification/controleur.php?action=login\">Login</a>" . " - <a href=\"../utilisateur/controleur.php?action=creer\">Sign Up</a>";
        } else {
            echo $_SESSION['mail'] . " - <a href=\"../authentification/controleur.php?action=logout\">Logout</a>";
        }
    ?>
</div>
<div id="menu">menu</div>
<div id="corps"><?php echo $corps; ?></div>
<footer>MOOC AppDyn</footer>

</body>
</html>
