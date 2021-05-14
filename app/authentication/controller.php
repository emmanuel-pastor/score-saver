<?php

if (!isset ($_GET["action"])) {
	die("Requête non autorisée");
}

require "model.php";

$action = $_GET['action'];

switch ($action) {
    case "login":
        login();
        break;
	case "logout":
	    logout();
	    break;
    default:
        require dirname(__FILE__) . "/../../public/404.php";
        break;
}

function login() {
	if (!isset ($_POST['email'])) { // No email => the user wants to log in
		$data = null;
		$errors = null;
		showForm($data, $errors);
	} else { // The form has been submitted
        $user = getEmailByEmailAndPassword($_POST['email'], $_POST['password']);
		$errors = validateForm($user);

		if (count($errors) == 0){
			authenticationSucceeded($user);

			// Redirect to the list of scores
			header ('Location:'.BASE_PATH.'score/list');
		} else {
			// Authentication failed
			showForm($_POST, $errors);
		}
	}
}

function showForm($data, $errors){
	$email = $data['email'] ?? '';
	$password = $data['password'] ?? '';
	$authError = $errors['auth'] ?? '';
	$action = BASE_PATH."authentication/login";

    $title = "Authentification";
    $stylesheet = "forms/auth_form.css";
	$content = <<<EOT
<form class="auth_form" name="auth_form" method="post" action="$action">
    <label class="email_label" for="login">Adresse e-mail</label>
    <div class="email input_with_error">
        <input class="email_input" type="email" name="email" value="$email" required aria-required="true" autofocus/>
        <p class="error"></p>
    </div>
    
    <label class="password_label" for="password">Mot de passe</label>
    <div class="password input_with_error">
        <input class="password_input" type="password" name="password" value="$password" required aria-required="true" />
        <p class="error">$authError</p>
    </div>
    <button class="base_button submit_button" id="validate_login" name='submit' type='submit'>Valider</button>
</form>
EOT;

	require dirname(__FILE__) . "/../_config/template.php";
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
	$_SESSION['email'] = $data['email'];
}

function logout(){
	session_destroy();

	header ('Location: '.BASE_PATH.'score/list');
}