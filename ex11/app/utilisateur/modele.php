<?php
require dirname(__FILE__) . "/../_config/DB.php";

function insertUser($user)
{
    $email = $user['mail'];
    $password = md5($user['password']);
    $isValid = 1; //TODO: pass to 0 before deploy
    $validationKey = $user['cle'];

    $db = openConnection();
    $query = "INSERT INTO utilisateur (`id`, `mail`, `password`, `valide`, `cle`)
		VALUES (NULL, '" . $email . "', '" . $password . "', '" . $isValid . "', '" . $validationKey . "');";
    $db->query($query);
    closeConnection($db);
}

function getUserByValidationKey($key): ?array
{
    $db = openConnection();
    $query = "SELECT * FROM utilisateur WHERE cle = '$key'";
    $result = $db->query($query);
    closeConnection($db);
    return $result->fetch_assoc();
}

function validateUser($key)
{
    $db = openConnection();
    $query = "UPDATE utilisateur SET `valide` = 1 WHERE cle = '$key';";
    $db->query($query);
    closeConnection($db);
}

function getAllUsers()
{
    $con = openConnection();
    $query = "SELECT * FROM utilisateur";
    $result = $con->query($query);
    closeConnection($con);
    return $result;
}

function getUserByEmail($email): ?array
{
    $db = openConnection();
    $query = "SELECT * FROM utilisateur WHERE mail = '$email' LIMIT 1";
    $result = $db->query($query);
    closeConnection($db);
    return $result->fetch_assoc();
}

