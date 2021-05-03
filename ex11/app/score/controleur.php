<?php

session_start();

if (!isset ($_GET["action"])) {
    die("requ&ecirc;te non autoris&eacute;e");
}

require "modele.php";

// récupération des données passées en GET
$action = $_GET['action'];

// traitement selon l'action
switch ($action) {
    case "lister":
        lister();
        break;
    case "creer":
        creer();
        break;
    case "modifier":
        modifier();
        break;
    case "supprimer":
        supprimer();
        break;
    default:
        echo "404 not found";
        break;
}

// fonctions
function lister()
{
    $titre = "Liste de scores";
    // récupération des enregistrements
    $result = recupereTous();
    // création code HTML
    $corps = "<ul>";
    while ($r = $result->fetch_assoc()) {
        $corps .= "<li>";
        $corps .= $r['id'] . ", " . $r['valeur'];
        if (isset($_SESSION['id']) && $_SESSION['id'] == $r['idUtilisateur']) {
            // liens
            $corps .= " - <a href=\"".BASE_PATH."score/modifier/" . $r['id'] . "\">Modifier</a>";
            $corps .= " | <a href=\"JavaScript:alertFunction(" . $r['id'] . ")\">Supprimer</a>";
        }
        $corps .= "</li>";
    }
    $corps .= "</ul>";
    // lien pour création
    if (isset($_SESSION['id'])) {
        $corps .= "<a href=\"".BASE_PATH."score/creer\">Cr&eacute;er</a>";
    }

    // lien pour authentification
    if (!isset($_SESSION['mail'])) {
        $loginLogout = "<a href=\"".BASE_PATH."authentification/login\">Login</a>" . " - <a href=\"".BASE_PATH."utilisateur/creer\">Sign Up</a>";
    } else {
        $loginLogout = $_SESSION['mail'] . " - <a href=\"".BASE_PATH."authentification/logout\">Logout</a>";
    }

    $lien = BASE_PATH . "score/supprimer/";
    $message = "";
    $corps .= "<script type='text/javascript'>function alertFunction(idE){var r=confirm('Voulez-vous' +
 ' vraiment supprimer cet enregistrement ?');if(r===true){var lien = '$lien'+idE;location.replace(lien);}}</script>";

    // affichage de la vue
    require dirname(__FILE__)."/../_config/gabarit.php";
}

function creer() {
    echo "TEST";
    $mode = "creation";
    // affichage du formulaire
    if (!isset ($_POST['valeur'])) {
        // pas de données => affichage
        $donnees = null;
        $erreurs = null;
        afficherFormulaire($mode, $donnees, $erreurs);
    } else {
        // données => test
        $erreurs = testDonnees($_POST);
        if ($erreurs == null) {
            // ajout
            ajouteEnregistrement($_POST);
            // redirection (sinon l'url demeurera action=creer)
            header('Location:'.BASE_PATH.'score/lister');
        } else {
            afficherFormulaire($mode, $_POST, $erreurs);
        }
    }
}

function supprimer()
{
    if (!isset ($_GET["id"])) {
        // pas de données
        die("requ&ecirc;te non autoris&eacute;e");
    }
    supprimeEnregistrement($_GET["id"]);
    header('Location:'.BASE_PATH.'score/lister');
    //lister();
}

function modifier()
{
    if (!isset ($_GET["id"]) && !isset ($_POST["id"])) {
        // pas de données
        die("requ&ecirc;te non autoris&eacute;e");
    }
    $mode = "modification";
    // affichage du formulaire
    if (!isset ($_POST["valeur"])) {
        // pas de données en POST (mais en GET) => affichage avec les données de l'enregistrement
        $donnees = recupereEnregistrementParId($_GET["id"]);
        $donnees['id'] = $_GET["id"];
        $erreurs = null;
        afficherFormulaire($mode, $donnees, $erreurs);
    } else {
        // données en POST => test
        $erreurs = testDonnees($_POST);
        if ($erreurs == null) {
            // ajout
            modifieEnregistrement($_POST["id"], $_POST);
            lister();
        } else {
            afficherFormulaire($mode, $_POST, $erreurs);
        }
    }
}

function afficherFormulaire($mode, $donnees, $erreurs)
{
    $loginLogout = "";
    if ($mode == "creation") {
        $titre = "Création";
        $action = BASE_PATH."score/creer";
    } else if ($mode == "modification") {
        $titre = "Modification";
        $action = BASE_PATH."score/modifier";
    }
    // création code HTML
    $valeur = $donnees['valeur'] ?? '';
    $id = $donnees['id'] ?? '';
    $erreurValeur = $erreurs['valeur'] ?? '';
    $annuleForm = BASE_PATH."score/lister";
    $corps = <<<EOT
<form id="creation-form" name="creation-form" method="post" action="$action">
<label for="valeur">Score</label>
<input id="valeur" type="text" name="valeur" value="$valeur" required aria-required="true" />
<p class="erreur">$erreurValeur</p>
<br><br>
<input type="button" value="Annuler" onclick="location.href='$annuleForm'">
<button name='submit' type='submit' id='submit'>Valider</button>
<input type='hidden' name='id' value='$id'/>
</form>
EOT;
    // affichage de la vue
    require dirname(__FILE__)."/../_config/gabarit.php";
}

function testDonnees($donnees)
{
    $erreurs = [];
    // test si le score est une valeur numérique
    if (!is_numeric($donnees['valeur'])) {
        $erreurs['valeur'] = "la valeur entrée doit être un nombre";
    }
    return $erreurs;
}

?>