<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link href=<?php echo BASE_PATH . "css/template.css" ?> rel="stylesheet"/>
    <link href=<?php echo BASE_PATH . "css/" . $stylesheet ?> rel="stylesheet"/>
    <script  src="https://code.jquery.com/jquery-1.11.0.min.js"></script>
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

<div class="content"><?php echo $content; ?></div>

<footer>
    <p>Développé par <u>Emmanuel Pastor</u></p>
    <a href="https://www.github.com/emmanuel-pastor" target={'_blank'} rel={'noopener noreferrer'}><img src=<?php echo BASE_PATH . "/img/logo-github.svg" ?> alt="Logo de Github"></a>
    <a href="https://www.linkedin.com/in/emmanuel-pastor-9a9a82201" target={'_blank'} rel={'noopener noreferrer'}><img src=<?php echo BASE_PATH . "/img/logo-linkedin.svg" ?> alt="Logo de LinkedIn"></a>
    <a href="https://www.simple-smart-apps.com" target={'_blank'} rel={'noopener noreferrer'}><img src=<?php echo BASE_PATH . "/img/ic-internet.svg" ?> alt="Icône Internet"></a>
    <p id="time"></p>
    <img class="logo-insa" src=<?php echo BASE_PATH . "/img/logo-insa.png" ?> alt="Logo de l'INSA Toulouse">
</footer>

</body>
<script>
    getTime();
    setInterval(getTime, 1000);
    function getTime() {
        $.ajax({
            url: '../public/time/get',
            dataType: "json",
            success: function(response) {
                let h = response.hour;
                let m = response.minute;
                let s = response.second;
                $('#time').html(h+":"+m+":"+s);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                $('#time').html(errorThrown);
            }
        });
    }
</script>
</html>
