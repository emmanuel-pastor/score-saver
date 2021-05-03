<?php
define ('BASE_PATH', 'http://localhost/Web/MO/score-saver/ex11/public/');

function parseUrl()
{
    $url = null;
    if (isset($_GET['url'])) {
        $url = explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
    }
    return $url;
}

// module et action par défaut
$module = "score";
$action = "lister";

// traitement de l'URL
$url = parseUrl();
if ($url) {
    $module = array_shift($url);
    if ($url) {
        $action = array_shift($url);
        if ($url) {
            $id = array_shift($url);
        }
    }
}

// Données passées en GET
$_GET['action'] = $action;
$_GET['id'] = $id ?? '';

// Lancement de l'action
if (
    file_exists(dirname(__FILE__) . "/../$module")
    && !is_file(dirname(__FILE__) . "/../$module")
) {
    require dirname(__FILE__)."/../$module/controleur.php";
} else {
    require dirname(__FILE__)."/../score/controleur.php";
}
