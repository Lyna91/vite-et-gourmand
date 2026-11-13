<?php

function getPdo(): PDO
{
    $host = 'mysql';                                              // le nom du service MySQL dans docker-compose
    $user = getenv('MYSQL_USER');                                // lit la valeur de MYSQL_USER dans le .env
    $db   = getenv('MYSQL_DATABASE');                            //le nom de la base
    $password = getenv('MYSQL_PASSWORD');                        //le mot de passe

    $dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";

    return new PDO($dsn, $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);
}

function getMongo(): MongoDB\Database
{
    $host = 'mongo';                                  // le nom du service MongoDB dans docker-compose
    $user = getenv('MONGO_ROOT_USER');                // lit la valeur dans le .env
    $password = getenv('MONGO_ROOT_PASSWORD');
    $db = getenv('MONGO_DATABASE');                   // vite_gourmand_stats

    $uri = 'mongodb://' . rawurlencode($user) . ':' . rawurlencode($password) . "@$host:27017";

    $client = new MongoDB\Client($uri);
    return $client->selectDatabase($db);
}
