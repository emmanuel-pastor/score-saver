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
        require dirname(__FILE__)."/../_config/gabarit.php";
        break;
}

function listALl()
{
    $result = getAllScores();

    $title = "Liste des scores";

    // Scores from the list
    $corps = "<ul>";
    while ($r = $result->fetch_assoc()) {
        $corps .= "<li>";
        $corps .= 'id: ' . $r['id'] . " | score: " . $r['valeur'];
        if (isset($_SESSION['id']) && $_SESSION['id'] == $r['idUtilisateur']) {
            $corps .= " - <a href=\"".BASE_PATH."score/modifier/" . $r['id'] . "\">Modifier</a>";
            $corps .= " | <a href=\"JavaScript:alertFunction(" . $r['id'] . ")\">Supprimer</a>";
        }
        $corps .= "</li>";
    }
    $corps .= "</ul>";

    // Creation link
    if (isset($_SESSION['id'])) {
        $corps .= "<a href=\"".BASE_PATH."score/creer\">Créer</a>";
    }

    // Deletion popup message
    $deletionLink = BASE_PATH . "score/supprimer/";
    $message = 'Voulez-vous vraiment supprimer cet enregistrement ?';
    $corps .= "<script type='text/javascript'>function alertFunction(idE){var r=confirm('$message');if(r===true){var lien = '$deletionLink'+idE;location.replace(lien);}}</script>";

    require dirname(__FILE__)."/../_config/gabarit.php";
}

function create() {
    $mode = "creation";

    if (!isset ($_POST['valeur'])) { // No value => user wants to create a new score
        $data = null;
        $errors = null;
        showForm($mode, $data, $errors);
    } else { // The form has been submitted
        $errors = validateForm($_POST);
        if ($errors == null) {
            insertScore($_POST);

            // Redirect to the list of scores
            header('Location:'.BASE_PATH.'score/lister');
        } else {
            showForm($mode, $_POST, $errors);
        }
    }
}

function modify()
{
    if (!isset ($_GET["id"]) && !isset ($_POST["id"])) { // Score to modify is not specified
        die("Requête non autorisée");
    }

    $mode = "modification";

    if (!isset ($_POST["valeur"])) { // No value => user wants to modify a score
        $score = getScoreById($_GET["id"]);
        $score['id'] = $_GET["id"];
        $errors = null;

        showForm($mode, $score, $errors);
    } else { // The form has been submitted
        $errors = validateForm($_POST);

        if ($errors == null) {
            updateScore($_POST["id"], $_POST);

            // Redirect to list of scores
            header('Location:'.BASE_PATH.'score/lister');
        } else {
            showForm($mode, $_POST, $errors);
        }
    }
}

function showForm($mode, $data, $errors)
{
    if ($mode == "creation") {
        $title = "Création";
        $action = BASE_PATH."score/creer";
    } else if ($mode == "modification") {
        $title = "Modification";
        $action = BASE_PATH."score/modifier";
    }

    $value = $data['valeur'] ?? '';
    $id = $data['id'] ?? '';
    $valueError = $errors['valeur'] ?? '';
    $idError = $errors['id'] ?? '';
    $cancelFormLink = BASE_PATH."score/lister";

    $corps = <<<EOT
<form id="creation-form" name="creation-form" method="post" action="$action">
    <label for="valeur">Score</label>
    <input id="valeur" type="text" name="valeur" value="$value" required aria-required="true" />
    <p class="erreur">$valueError</p>
    <input type='hidden' name='id' value='$id'/>
    <p class="erreur">$idError</p>
    <br>
    <input type="button" value="Annuler" onclick="location.href='$cancelFormLink'">
    <button name='submit' type='submit' id='submit'>Valider</button>
</form>
EOT;

    require dirname(__FILE__)."/../_config/gabarit.php";
}

function validateForm($data): array
{
    $errors = [];
    if (!is_numeric($data['valeur'])) {
        $errors['valeur'] = "La valeur entrée doit être un nombre";
    }
    if (!isset($data['id'])) {
        $errors['id'] = "L'identifiant du score n'a pas été renseigné.";
    }
    return $errors;
}

function delete()
{
    if (!isset ($_GET["id"])) {
        die("Requête non autorisée");
    }

    deleteScoreById($_GET["id"]);

    header('Location:'.BASE_PATH.'score/lister');
}