<?php
require dirname(__FILE__) . "/../_config/DB.php";

function insertScore($score){
	$value = $score['valeur'];
	$con = openConnection();
	$query = "INSERT INTO score (`id`, `valeur`, `date`, `idUtilisateur`)
		VALUES (NULL, '".$value."', '".date('Y-m-d')."', '".$_SESSION['id']."');";
	$con->query($query);
	closeConnection($con);
}

function getAllScores(){
	$db = openConnection();
	$query = "SELECT * FROM score";
	$result = $db->query($query);
	closeConnection($db);
	return $result;
}

function getScoreById($id): ?array
{
	$db = openConnection();
	$query = "SELECT * FROM score WHERE id = $id LIMIT 1";
	$result = $db->query($query);
	closeConnection($db);
	return $result->fetch_assoc();
}

function updateScore($id, $updatedScore){
	$value = $updatedScore['valeur'];
	$db = openConnection();
	$query = "UPDATE score SET `valeur` = $value WHERE id = $id;";
	$db->query($query);
	closeConnection($db);
}

function deleteScoreById($id){
	$db = openConnection();
	$query = "DELETE FROM score WHERE id = $id";
	$db->query($query);
	closeConnection($db);
}

