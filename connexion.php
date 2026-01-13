<?php
try {
    $dns = 'mysql:host=localhost;dbname=bibliodrive;charset=utf8mb4';
    $utilisateur = 'root';
    $motDePasse = '';

    $connexion = new PDO($dns, $utilisateur, $motDePasse, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (Exception $e) {
    die("Connexion à MySQL impossible : " . $e->getMessage());
}
