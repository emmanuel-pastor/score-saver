<?php
session_start();

function parseUrl()
{
    $url = null;

    if (isset($_GET['url'])) {
        $url = explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
    }
    return $url;
}

$module = DEFAULT_MODULE;
$action = DEFAULT_ACTION;

// Processing of the URL
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

$_GET['action'] = $action;
$_GET['id'] = $id ?? '';

// Display the required page
if (
    file_exists(dirname(__FILE__) . "/../$module")
    && !is_file(dirname(__FILE__) . "/../$module")
) {
    require dirname(__FILE__) . "/../$module/controleur.php";
} else {
    require dirname(__FILE__) . "/../score/controleur.php";
}
