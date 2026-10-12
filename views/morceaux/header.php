<?php

/** @var string $titre */ ?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($titre) ?> – Vite &amp; Gourmand</title>
</head>

<body>
    <header>
        <nav>
            <ul>
                <li><a href="/">Accueil</a></li>
                <li><a href="/menus">Les Menus</a></li>
                <li><a href="/contact">Contact</a></li>
                <li><a href="/connexion">Connexion</a></li>
            </ul>
        </nav>
    </header>
    <main>