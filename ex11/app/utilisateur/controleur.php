<?php

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
    case "valider":
        valider();
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
    $titre = "Liste d'enregistrements";
    // récupération des enregistrements
    $result = recupereTous();
    // création code HTML
    $corps = "<ul>";
    while ($r = $result->fetch_assoc()) {
        $corps .= "<li>";
        $corps .= $r['id'] . ", " . $r['mail'];
        // liens
        $corps .= " - <a href=\"".BASE_PATH."utilisateur/modifier/" . $r['id'] . "\">Modifier</a>";
        $corps .= " | <a href=\"".BASE_PATH."utilisateur/supprimer/" . $r['id'] . "\">Supprimer</a>";
        $corps .= "</li>";
    }
    $corps .= "</ul>";
    // lien pour s'enregistrer
    $corps .= "<a href=\"".BASE_PATH."utilisateur/creer\">S'enregistrer</a>";
    // affichage de la vue
    require "vue.php";
}

function creer()
{
    $mode = "creation";
    // affichage du formulaire
    if (!isset ($_POST['mail'])) {
        // pas de données => affichage
        $donnees = null;
        $erreurs = null;
        afficherFormulaire($mode, $donnees, $erreurs);
    } else {
        // données => test
        $erreurs = testDonnees($_POST);
        if ($erreurs == null) {
            $donnees = $_POST;
            // génération aléatoire d'une clé
            $cle = md5(microtime(TRUE) * 100000);
            $donnees['cle'] = $cle;
            // envoi du mail
            //TODO: restore before deploy //envoiMailConfirmation($donnees);
            if (compteExisteDeja($donnees['mail'])) {
                $titre = "Validation";
                $corps = "Un compte avec le mail " . $donnees['mail'] . " existe déjà<br/><a href=\"".BASE_PATH."score/lister\">Revenir à la liste des scores</a>";
            } else {
                // ajout de l'enregistrement
                ajouteEnregistrement($donnees);
                // message
                $titre = "Validation";
                $corps = "Votre compte a été créé. <br/><a href=\"".BASE_PATH."score/lister\">Revenir à la liste des scores</a>"; //TODO: Restore before deploy
                /*"Votre compte à été créé. Un mail de confirmation
     vous a été envoyé à l'adresse ".$donnees['mail'].".";*/
            }
            require "vue.php";
        } else {
            afficherFormulaire($mode, $_POST, $erreurs);
        }
    }
}

function compteExisteDeja($mail)
{
    $utilisateur = recupereUtilisateurParMail($mail);
    return isset($utilisateur);
}


function valider()
{
    // validation d'un compte
    if (!isset($_GET["cle"])) {
        // pas de données
        die("requ&ecirc;te non autoris&eacute;e");
    }
    $cle = $_GET["cle"];
    echo $cle;
    // recherche de l'utilisateur
    $utilisateur = recupereEnregistrementParCle($cle);
    if (!isset($utilisateur)) {
        // aucun utilisateur
        die("cl&egrave; non valide");
    }
    if ($utilisateur['valide'] == 1) {
        // affichage de la vue
        $titre = "Validation";
        $corps = "Votre compte à déjà été validé. <a href=\"".BASE_PATH."score/lister\">Revenir à la liste des scores</a>";
        require "vue.php";
    } else {
        // validation
        validerUtilisateur($cle);
        // affichage de la vue
        $titre = "Validation";
        $corps = "Votre compte à bien été validé. Vous pouvez désormais vous connecter. <a href=\"".BASE_PATH."score/lister\">Revenir à la liste des scores</a>";
        require "vue.php";
    }
}


function afficherFormulaire($mode, $donnees, $erreurs)
{
    if ($mode == "creation") {
        $titre = "Création";
        $action = BASE_PATH . "utilisateur/creer";
    } else if ($mode == "modification") {
        $titre = "Modification";
        $action = BASE_PATH . "utilisateur/modifier";
    }
    // création code HTML
    $id = $donnees['id'] ?? '';
    $mail = $donnees['mail'] ?? '';
    $password = $donnees['password'] ?? '';
    $erreurMail = $erreurs['mail'] ?? '';
    $erreurPassword = $erreurs['password'] ?? '';
    $corps = <<<EOT
<form id="creation-form" name="creation-form" method="post" action="$action">
<label for="mail">Mail</label>
<input id="mail" type="email" name="mail" value="$mail" required aria-required="true" />
<p class="erreur">$erreurMail</p>
<br>
<label for="password">Password</label>
<input id="password" type="password" name="password" value="$password" required aria-required="true" />
<p class="erreur">$erreurPassword</p>
<br>
<button name='submit' type='submit' id='submit'>Valider</button>
<input type='hidden' name='id' value='$id'/>
</form>
EOT;
    // affichage de la vue
    require "vue.php";
}

function testDonnees($donnees)
{
    $erreurs = array();
    return $erreurs;
}

function envoiMailConfirmation($donnees)
{
    $destinataire = $donnees['mail'];
    $cle = $donnees['cle'];
    $sujet = "Activer votre compte";
    $entete = "From: inscription@ssa-apis.com";
    // Le lien d'activation est composé de la clé(cle)
    $message = 'Bienvenue sur VotreSite,
Pour activer votre compte, veuillez cliquer sur le lien ci dessous
ou copier/coller dans votre navigateur internet.
http://votresite.com/utilisateur/controleur.php?action=validation&cle=' . urlencode($cle) . '
---------------
Ceci est un mail automatique, Merci de ne pas y répondre.';
    // envoi
    mail($destinataire, $sujet, $message, $entete);
}

?>