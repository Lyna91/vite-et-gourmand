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
        $id = (int) ($_GET['menu'] ?? 0);
        $menu = null;
        $utilisateur = null;

        try {
            $pdo = getPdo();
            $menuManager = new MenuManager($pdo);
            $menu = $menuManager->findById($id);
            $utilisateurManager = new UtilisateurManager($pdo);
            $utilisateur = $utilisateurManager->trouverParId($_SESSION['id_utilisateur']);
        } catch (PDOException $e) {
            // base indisponible : $menu reste null, affichage de la page 404
        }
        if ($menu === null) {
            http_response_code(404);
            $this->afficher('introuvable', [
                'titre' => 'Menu introuvable',
            ]);
            return;
        }
        $this->afficher('commande', [
            'titre' => 'Commander',
            'menu' => $menu,
            'utilisateur' => $utilisateur
        ]);

    }

    
}
