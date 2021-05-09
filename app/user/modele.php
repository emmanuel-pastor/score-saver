<?php
require dirname(__FILE__) . "/../_config/DB.php";

function insertUser($user)
{
    $email = $user['email'];
    $password = md5($user['password']);
    $isValid = 1; //TODO: pass to 0 before deploy
    $validationKey = $user['validation_key'];

    $db = openConnection();
    $query = "INSERT INTO user (`id`, `email`, `password`, `is_valid`, `validation_key`)
		VALUES (NULL, '" . $email . "', '" . $password . "', '" . $isValid . "', '" . $validationKey . "');";
    $db->query($query);
    closeConnection($db);
}

function getUserByValidationKey($key): ?array
{
    $db = openConnection();
    $query = "SELECT * FROM user WHERE validation_key = '$key'";
    $result = $db->query($query);
    closeConnection($db);
    return $result->fetch_assoc();
}

function validateUser($key)
{
    $db = openConnection();
    $query = "UPDATE user SET `is_valid` = 1 WHERE validation_key = '$key';";
    $db->query($query);
    closeConnection($db);
}

function getAllUsers()
{
    $con = openConnection();
    $query = "SELECT * FROM user";
    $result = $con->query($query);
    closeConnection($con);
    return $result;
}

function getUserByEmail($email): ?array
{
    $db = openConnection();
    $query = "SELECT * FROM user WHERE email = '$email' LIMIT 1";
    $result = $db->query($query);
    closeConnection($db);
    return $result->fetch_assoc();
}

