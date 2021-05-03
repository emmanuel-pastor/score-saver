<?php
require dirname(__FILE__)."/../_config/BD.php";

function recupereTous(){
	// récupération de tous les enregistrements
	$con = connexion();
	$query = "SELECT * FROM score";
	$result = $con->query($query);
	fermeture($con);
	return $result;
}

function ajouteEnregistrement($donnees){
	$valeur = $donnees['valeur'];
	// ajout d'un enregistrement
	$con = connexion();
	$query = "INSERT INTO score (`id`, `valeur`, `date`, `idUtilisateur`)
		VALUES (NULL, '".$valeur."', '".date('Y-m-d')."', '".$_SESSION['id']."');";
	$result = $con->query($query);
	fermeture($con);	
}
function recupereEnregistrementParId($id){
	// récupération d'un enregistrement
	$con = connexion();
	$query = "SELECT * FROM score WHERE id = $id";
	$result = $con->query($query);
	fermeture($con);
	return $result->fetch_assoc();
}	
function modifieEnregistrement($id, $donnees){
	$valeur = $donnees['valeur'];
	// récupération d'un enregistrement
	$con = connexion();
	$query = "UPDATE score SET `valeur` = $valeur WHERE id = $id;";
	$result = $con->query($query);
	fermeture($con);
}
function supprimeEnregistrement($id){
	// suppression d'un enregistrement
	$con = connexion();
	$query = "DELETE FROM score WHERE id = $id";
	$result = $con->query($query);
	fermeture($con);
}	
	
?>

