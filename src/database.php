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
