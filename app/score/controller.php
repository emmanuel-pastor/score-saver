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
        require dirname(__FILE__) . "/../../public/404.php";
        break;
}

function listALl()
{
    $result = getAllScores();

    $title = "Liste des scores";
    $stylesheet = "scores.css";

    // Scores from the list
    $content = "<div class='score_list'>";
    $content .= "<span class='score_list_item'><span>id</span><span><strong>score</strong></span></span>";
    while ($r = $result->fetch_assoc()) {
        $content .= "<span class='score_list_item score_card'><span>" . $r['id'] . "</span><span><strong>" . $r['value'] . "</span></strong></span>";
        if (isset($_SESSION['id']) && $_SESSION['id'] == $r['user_id']) {
            $content .= "<a class='action_button modify_button' href=\"".BASE_PATH."score/modify/" . $r['id'] . "\"><img src=\"" . BASE_PATH . "img/ic-edit.svg\" alt='Modifier le score'/></a>";
            $content .= "<a class='action_button delete_button' href=\"JavaScript:alertFunction(" . $r['id'] . ")\"><img src=\"" . BASE_PATH . "img/ic-delete.svg\" alt='SUpprimer le score'/></a>";
        }
    }
    $content .= "</div>";

    // Creation link
    if (isset($_SESSION['id'])) {
        $content .= "<a class='base_button create_score_button' href=\"".BASE_PATH."score/create\">Ajouter score</a>";
    }

    // Deletion popup message
    $deletionLink = BASE_PATH . "score/delete/";
    $message = 'Voulez-vous vraiment supprimer cet enregistrement ?';
    $content .= "<script type='text/javascript'>function alertFunction(idE){var r=confirm('$message');if(r===true){var lien = '$deletionLink'+idE;location.replace(lien);}}</script>";

    require dirname(__FILE__) . "/../_config/template.php";
}

function create() {
    $mode = "creation";

    if (!isset ($_POST['value'])) { // No value => user wants to create a new score
        $data = null;
        $errors = null;
        showForm($mode, $data, $errors);
    } else { // The form has been submitted
        $errors = validateForm($_POST);
        if ($errors == null) {
            insertScore($_POST);

            // Redirect to the list of scores
            header('Location:'.BASE_PATH.'score/list');
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

    if (!isset ($_POST["value"])) { // No value => user wants to modify a score
        $score = getScoreById($_GET["id"]);
        $score['id'] = $_GET["id"];
        $errors = null;

        showForm($mode, $score, $errors);
    } else { // The form has been submitted
        $errors = validateForm($_POST);

        if ($errors == null) {
            updateScore($_POST["id"], $_POST);

            // Redirect to list of scores
            header('Location:'.BASE_PATH.'score/list');
        } else {
            showForm($mode, $_POST, $errors);
        }
    }
}

function showForm($mode, $data, $errors)
{
    if ($mode == "creation") {
        $title = "Création";
        $action = BASE_PATH."score/create";
    } else if ($mode == "modification") {
        $title = "Modification";
        $action = BASE_PATH."score/modify";
    }

    $value = $data['value'] ?? '';
    $id = $data['id'] ?? '';
    $valueError = $errors['value'] ?? '';
    $idError = $errors['id'] ?? '';
    $cancelFormLink = BASE_PATH."score/list";

    $stylesheet = "forms/score_form.css";
    $content = <<<EOT
<form class="score_form" name="score_form" method="post" action="$action">
    <label for="value">Score</label>
    <div class="input_with_error score">
        <input class="score_input" type="text" name="value" value="$value" required aria-required="true" autofocus/>
        <p class="error">$valueError</p>
    </div>
    <div class="submit_score">
        <input type='hidden' name='id' value='$id'/>
        <p class="error id_error">$idError</p>
        <button class="base_button cancel_button" type="button" onclick="location.href='$cancelFormLink'">Annuler</button>
        <button class="base_button submit_button" id="validate_score_button" name='submit' type='submit' id='submit'>Valider</button>
    </div>
</form>
EOT;

    require dirname(__FILE__) . "/../_config/template.php";
}

function validateForm($data): array
{
    $errors = [];
    if (!is_numeric($data['value'])) {
        $errors['value'] = "La valeur entrée doit être un nombre";
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

    header('Location:'.BASE_PATH.'score/list');
}