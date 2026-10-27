<?php

class CommandeController extends Controller
{
    public function commande(): void
    {
        // 1. pas connecté → direction la connexion
        if (!isset($_SESSION['id_utilisateur'])) {
            header('Location: /connexion');
            exit;
        }

        // 2. connecté → on affiche la page
        $this->afficher('commande', [
            'titre' => 'Commander'
        ]);
    }
}
