<?php

class Controller
{
    protected function afficher(string $vue, array $donnees = []): void
    {
        $horaires = [];
        try {
            $horaireManager = new HoraireManager(getPdo());
            $horaires = $horaireManager->findAll();
        } catch (PDOException $e) {
            // si la base ne répond pas, le footer s'affiche sans les horaires
        }
        $donnees['horaires'] = $horaires;
        
        extract($donnees);
        require __DIR__ . '/../views/' . $vue . '.php';
    }
}
