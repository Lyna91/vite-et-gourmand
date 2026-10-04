<?php

$host = 'mysql';                    // le nom du service MySQL dans docker-compose
$user = getenv('MYSQL_USER');       // lit la valeur de MYSQL_USER dans le .env
$db   = getenv('MYSQL_DATABASE');                         // à toi : le nom de la base
$password = getenv('MYSQL_PASSWORD');                         // à toi : le mot de passe

$dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";


try {
    $pdo = new PDO($dsn, $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);
    echo "Connexion MySQL OK";   // message si ça marche
} catch (PDOException $e) {
    echo "Erreur de connexion : " . $e->getMessage();
}
