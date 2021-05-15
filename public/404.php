<?php
include_once "../app/_config/constants.php";

$stylesheet = "http-error/404.css";
$title = "404 - Page introuvable";
$imageLink = BASE_PATH . "img/404.webp";
$content = <<<EOT
<img class="http_error_image" src=$imageLink alt='Erreur 404'/>
<p class="http_error_message">Page introuvable. Vous vous êtes perdu ?</p>
EOT;

require dirname(__FILE__) . "/../app/_config/template.php";