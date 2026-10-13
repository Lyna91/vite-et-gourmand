<?php

class Controller
{
    protected function afficher(string $vue, array $donnees = []): void
    {
        extract($donnees);
        require __DIR__ . '/../views/' . $vue . '.php';
    }
}