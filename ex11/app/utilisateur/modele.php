<?php
require dirname(__FILE__)."/../_config/BD.php";

function ajouteEnregistrement($donnees){
	$mail = $donnees['mail'];
	$password = $donnees['password'];
	$valide = 1; //TODO: pass to 0 before deploy
	$cle = $donnees['cle'];
	// ajout d'un enregistrement
	$con = connexion();
	$query = "INSERT INTO utilisateur (`id`, `mail`, `password`, `valide`, `cle`)
		VALUES (NULL, '".$mail."', '".$password."', '".$valide."', '".$cle."');";
	$result = $con->query($query);
	fermeture($con);	
}

function recupereEnregistrementParCle($cle) {
	// récupération d'un enregistrement
	$con = connexion();
	$query = "SELECT * FROM utilisateur WHERE cle = '$cle'";
	$result = $con->query($query);
	fermeture($con);
	return $result->fetch_assoc();
}
function validerUtilisateur($cle) {
	// validation d'un utilisateur
	$con = connexion();
	$query = "UPDATE utilisateur SET `valide` = 1 WHERE cle = '$cle';";
	$result = $con->query($query);
	fermeture($con);
}

function recupereTous(){
	// récupération de tous les enregistrements
	$con = connexion();
	$query = "SELECT * FROM utilisateur";
	$result = $con->query($query);
	fermeture($con);
	return $result;
}

function recupereUtilisateurParMail($mail) {
	$con = connexion();
	$query = "SELECT * FROM utilisateur WHERE mail = '$mail' LIMIT 1";
	$result = $con->query($query);
	fermeture($con);
	return $result->fetch_assoc();
}
	
	
?>

