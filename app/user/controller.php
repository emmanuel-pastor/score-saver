<?php

if (!isset ($_GET["action"])) {
    die("Requête non autorisée");
}

require "model.php";

$action = $_GET['action'];

switch ($action) {
    case "list":
        listAll();
        break;
    case "create":
        create();
        break;
    case "modify":
        modify();
        break;
    case "delete":
        delete();
        break;
    default:
        $content = "<h2>Erreur 404</h2><br /><a href=\"" . BASE_PATH . "\">Revenir à l'acceuil</a>";
        require dirname(__FILE__) . "/../_config/template.php";
        break;
}

function listAll()
{
    $result = getAllUsers();

    $title = "Liste des utilisateurs";
    $content = "<ul>";
    while ($r = $result->fetch_assoc()) {
        $content .= "<li>";
        $content .= $r['id'] . ", " . $r['email'];
        $content .= " - <a href=\"" . BASE_PATH . "user/modify/" . $r['id'] . "\">Modifier</a>";
        $content .= " | <a href=\"" . BASE_PATH . "user/delete/" . $r['id'] . "\">Supprimer</a>";
        $content .= "</li>";
    }
    $content .= "</ul>";

    require dirname(__FILE__) . "/../_config/template.php";
}

function create()
{
    $mode = "creation";

    if (!isset ($_POST['email'])) { // No email => user needs to be created
        $data = null;
        $error = null;
        showForm($mode, $data, $error);
    } else { // The form has been submitted
        $data = $_POST;
        $error = validateForm($data);

        if ($error == null) {
            // Generate a random validation key
            $validationKey = md5(microtime(TRUE) * 100000);
            $data['validation_key'] = $validationKey;

            $title = "Validation";
            if (accountAlreadyExists($data['email'])) {
                $content = "Un compte avec le mail " . $data['email'] . " existe déjà<br/><a href=\"" . BASE_PATH . "score/list\">Revenir à la liste des scores</a>";
            } else {
                insertUser($data);
                //TODO: restore before deploy //sendConfirmationEmail($donnees);

                $content = "Votre compte a été créé. <br/><a href=\"" . BASE_PATH . "score/list\">Revenir à la liste des scores</a>"; //TODO: Restore before deploy
                /*"Votre compte à été créé. Un mail de confirmation
     vous a été envoyé à l'adresse ".$donnees['email'].".";*/
            }

            require dirname(__FILE__) . "/../_config/template.php";
        } else {
            showForm($mode, $data, $error);
        }
    }
}

function showForm($mode, $data, $errors)
{
    if ($mode == "creation") {
        $title = "Création d'un compte";
        $action = BASE_PATH . "user/create";
    } else if ($mode == "modification") {
        $title = "Modification";
        $action = BASE_PATH . "user/modify";
    }

    $id = $data['id'] ?? '';
    $email = $data['email'] ?? '';
    $password = $data['password'] ?? '';
    $emailError = $errors['email'] ?? '';
    $passwordError = $errors['password'] ?? '';
    $idError = $errors['id'] ?? '';

    $content = <<<EOT
<form id="creation-form" name="creation-form" method="post" action="$action">
    <label for="email">Mail</label>
    <input id="email" type="email" name="email" value="$email" required aria-required="true" />
    <p class="error">$emailError</p>
    <br>
    <label for="password">Password</label>
    <input id="password" type="password" name="password" value="$password" required aria-required="true" />
    <p class="error">$passwordError</p>
    <br>
    <p class="error">$idError</p>
    <input type='hidden' name='id' value='$id'/>
    <button name='submit' type='submit' id='submit'>Valider</button>
</form>
EOT;

    require dirname(__FILE__) . "/../_config/template.php";
}

function validateForm($data): array
{
    $error = array();
    if (!isset($data['email'])) {
        $error['email'] = "Mail non renseigné";
    }
    if (!isset($data['password'])) {
        $error['password'] = "Mot de passe non renseigné";
    }
    if (!isset($data['id'])) {
        $error['id'] = "Identifiant non spécifié";
    }
    return $error;
}

function accountAlreadyExists($email): bool
{
    $user = getUserByEmail($email);
    return isset($user);
}

function sendConfirmationEmail($data)
{
    $email = $data['email'];
    $validationKey = $data['validation_key'];
    $subject = "Activer votre compte " . HOST_NAME;
    $header = "From: " . CONFIRMATION_EMAIL;
    $message = 'Bienvenue sur ' . HOST_NAME . ',
Pour activer votre compte, veuillez cliquer sur le lien ci-dessous
ou le copier/coller dans votre navigateur.
https://' . HOST_NAME . '/user/validation/' . urlencode($validationKey) . '
---------------
Ceci est un mail automatique, Merci de ne pas y répondre.';

    // Send email
    mail($email, $subject, $message, $header);
}

function modify()
{
    $content = "Page pas encore implémentée";
    require dirname(__FILE__) . "/../_config/template.php";
    //todo implement
    //if admin, make update request
    //else show error
}

function delete()
{
    $content = "Page pas encore implémentée";
    require dirname(__FILE__) . "/../_config/template.php";
    //todo implement
    //if admin, make delete request
    //else show error
}