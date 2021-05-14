<?php
include_once "../app/_config/constants.php";

$stylesheet = "http-error/403.css";
$imageLink = BASE_PATH . "img/403.webp";
$content = <<<EOT
<img class="http_error_image" src=$imageLink alt='Erreur 403. Accès interdit'/>
<p class="http_error_message">Accès non autorisé</p>
EOT;

require dirname(__FILE__) . "/../app/_config/template.php";