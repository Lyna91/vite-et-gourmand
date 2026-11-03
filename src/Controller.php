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

    protected function exigerRole(array $rolesAutorises): void
    {
        // 1. pas connecté → connexion
        if (!isset($_SESSION['id_utilisateur'])) {
            header('Location: /connexion');
            exit;
        }

        // 2. connecté, mais pas le bon rôle → accès refusé
        if (!in_array($_SESSION['id_role'], $rolesAutorises)) {
            http_response_code(403);
            $this->afficher('acces-refuse', [
                'titre' => 'Accès refusé',
            ]);
            exit;
        }
    }
}
