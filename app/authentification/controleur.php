<?php

if (!isset ($_GET["action"])) {
	die("Requête non autorisée");
}

require "modele.php";

$action = $_GET['action'];

switch ($action) {
    case "login":
        login();
        break;
	case "logout":
	    logout();
	    break;
    default:
        $corps = "<h2>Erreur 404</h2><br /><a href=\"" . BASE_PATH . "\">Revenir à l'acceuil</a>";
        require dirname(__FILE__) . "/../_config/gabarit.php";
        break;
}

function login() {
	if (!isset ($_POST['mail'])) { // No email => the user wants to log in
		$data = null;
		$errors = null;
		showForm($data, $errors);
	} else { // The form has been submitted
        $user = getEmailByEmailAndPassword($_POST['mail'], $_POST['password']);
		$errors = validateForm($user);

		if (count($errors) == 0){
			authenticationSucceeded($user);

			// Redirect to the list of scores
			header ('Location:'.BASE_PATH.'score/lister');
		} else {
			// Authentication failed
			showForm($_POST, $errors);
		}
	}
}

function showForm($data, $errors){
	$email = $data['mail'] ?? '';
	$password = $data['password'] ?? '';
	$authError = $errors['auth'] ?? '';
	$action = BASE_PATH."authentification/login";

    $title = "Authentification";
	$corps = <<<EOT
<form id="creation-form" name="creation-form" method="post" action="$action">
    <label for="login">Login</label>
    <input id="mail" type="email" name="mail" value="$email" required aria-required="true" />
    <p class="erreur"></p>
    <label for="password">Password</label>
    <input id="password" type="password" name="password" value="$password" required aria-required="true" />
    <p class="erreur">$authError</p>
    <br><br>
    <button name='submit' type='submit' id='submit'>Valider</button>
</form>
EOT;

	require dirname(__FILE__) . "/../_config/gabarit.php";
}

function validateForm($data): array
{
	$errors = array();
	if($data == null) {
		$errors['auth'] = "Pas d'utilisateur avec ces identifiants";
	}
	return $errors;
}

function authenticationSucceeded($data){
    $_SESSION['id'] = $data['id'];
	$_SESSION['mail'] = $data['mail'];
}

function logout(){
	session_destroy();

	header ('Location: '.BASE_PATH.'score/lister');
}