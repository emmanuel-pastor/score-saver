<?php
require dirname(__FILE__) . "/../_config/DB.php";

function insertScore($score){
	$value = $score['value'];
	$con = openConnection();
	$query = "INSERT INTO score (`id`, `value`, `date`, `user_id`)
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
	$value = $updatedScore['value'];
	$db = openConnection();
	$query = "UPDATE score SET `value` = $value WHERE id = $id;";
	$db->query($query);
	closeConnection($db);
}

function deleteScoreById($id){
	$db = openConnection();
	$query = "DELETE FROM score WHERE id = $id";
	$db->query($query);
	closeConnection($db);
}

