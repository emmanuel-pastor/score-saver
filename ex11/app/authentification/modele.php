<?php
require dirname(__FILE__) . "/../_config/DB.php";

function getEmailByEmailAndPassword($email, $password): ?array
{
    $db = openConnection();
    $query = "SELECT * FROM utilisateur WHERE mail = '$email' AND password = '$password'";
    $result = $db->query($query);
    closeConnection($db);
    return $result->fetch_assoc();
}
