<?php

if (!isset ($_GET["action"])) {
    die("Requête non autorisée");
}

require "modele.php";

$action = $_GET['action'];

switch ($action) {
    case "lister":
        listAll();
        break;
    case "creer":
        create();
        break;
    case "modifier":
        modify();
        break;
    case "supprimer":
        delete();
        break;
    default:
        $corps = "<h2>Erreur 404</h2><br /><a href=\"" . BASE_PATH . "\">Revenir à l'acceuil</a>";
        require "vue.php";
        break;
}

function listAll()
{
    $result = getAllUsers();

    $title = "Liste des utilisateurs";
    $corps = "<ul>";
    while ($r = $result->fetch_assoc()) {
        $corps .= "<li>";
        $corps .= $r['id'] . ", " . $r['mail'];
        $corps .= " - <a href=\"" . BASE_PATH . "utilisateur/modifier/" . $r['id'] . "\">Modifier</a>";
        $corps .= " | <a href=\"" . BASE_PATH . "utilisateur/supprimer/" . $r['id'] . "\">Supprimer</a>";
        $corps .= "</li>";
    }
    $corps .= "</ul>";

    require "vue.php";
}

function create()
{
    $mode = "creation";

    if (!isset ($_POST['mail'])) { // No email => user needs to be created
        $data = null;
        $error = null;
        showForm($mode, $data, $error);
    } else { // The form has been submitted
        $data = $_POST;
        $error = validateForm($data);

        if ($error == null) {
            // Generate a random validation key
            $validationKey = md5(microtime(TRUE) * 100000);
            $data['cle'] = $validationKey;

            $title = "Validation";
            if (accountAlreadyExists($data['mail'])) {
                $corps = "Un compte avec le mail " . $data['mail'] . " existe déjà<br/><a href=\"" . BASE_PATH . "score/lister\">Revenir à la liste des scores</a>";
            } else {
                insertUser($data);
                //TODO: restore before deploy //sendConfirmationEmail($donnees);

                $corps = "Votre compte a été créé. <br/><a href=\"" . BASE_PATH . "score/lister\">Revenir à la liste des scores</a>"; //TODO: Restore before deploy
                /*"Votre compte à été créé. Un mail de confirmation
     vous a été envoyé à l'adresse ".$donnees['mail'].".";*/
            }

            require "vue.php";
        } else {
            showForm($mode, $data, $error);
        }
    }
}

function showForm($mode, $data, $errors)
{
    if ($mode == "creation") {
        $title = "Création d'un compte";
        $action = BASE_PATH . "utilisateur/creer";
    } else if ($mode == "modification") {
        $title = "Modification";
        $action = BASE_PATH . "utilisateur/modifier";
    }

    $id = $data['id'] ?? '';
    $email = $data['mail'] ?? '';
    $password = $data['password'] ?? '';
    $emailError = $errors['email'] ?? '';
    $passwordError = $errors['password'] ?? '';
    $idError = $errors['id'] ?? '';

    $corps = <<<EOT
<form id="creation-form" name="creation-form" method="post" action="$action">
    <label for="mail">Mail</label>
    <input id="mail" type="email" name="mail" value="$email" required aria-required="true" />
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

    require "vue.php";
}

function validateForm($data): array
{
    $error = array();
    if (!isset($data['mail'])) {
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
    $email = $data['mail'];
    $validationKey = $data['cle'];
    $subject = "Activer votre compte " . HOST_NAME;
    $header = "From: " . CONFIRMATION_EMAIL;
    $message = 'Bienvenue sur ' . HOST_NAME . ',
Pour activer votre compte, veuillez cliquer sur le lien ci-dessous
ou le copier/coller dans votre navigateur.
https://' . HOST_NAME . '/utilisateur/validation/' . urlencode($validationKey) . '
---------------
Ceci est un mail automatique, Merci de ne pas y répondre.';

    // Send email
    mail($email, $subject, $message, $header);
}

function modify()
{
    $corps = "Page pas encore implémentée";
    require "vue.php";
    //todo implement
    //if admin, make update request
    //else show error
}

function delete()
{
    $corps = "Page pas encore implémentée";
    require "vue.php";
    //todo implement
    //if admin, make delete request
    //else show error
}