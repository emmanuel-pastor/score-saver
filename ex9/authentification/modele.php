<?php
require "../_config/BD.php";

function recupereEnregistrementParMailEtPassword($donnees)
{
    // récupération d'un enregistrement
    $mail = $donnees['mail'];
    $password = $donnees['password'];
    $con = connexion();
    $query = "SELECT * FROM utilisateur WHERE mail = '$mail' AND password = '$password'";
    $result = $con->query($query);
    return $result->fetch_assoc();
    fermeture($con);
}

?>

