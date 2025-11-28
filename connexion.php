<?php

// Connexion au serveur

try {

  $dns = 'mysql:host=localhost;dbname=bibliodrive'; // dbname : nom de la base

  $utilisateur = 'root'; // root sur vos postes

  $motDePasse = ''; // pas de mot de passe sur vos postes

  $connexion = new PDO( $dns, $utilisateur, $motDePasse );

} catch (Exception $e) {

  echo "Connexion à MySQL impossible : ", $e->getMessage();

  die();

}

$sql = "SELECT titre, photo, anneeparution FROM livre ORDER BY dateajout DESC LIMIT 3";
$result = $connexion->query($sql);
$livres = $result->fetchAll();
?>

