<?php

class MenuController extends Controller
{
    public function liste(): void //: void veut dire « cette méthode ne renvoie rien ». Elle affiche, mais elle ne fait pas de return.
    {
        // 1. préparer les données
        $titre = 'Nos menus';
        $menus = [];
        $erreur = null;

        try {
            $menuManager = new MenuManager(getPdo());
            $menus = $menuManager->findAll();
        } catch (PDOException $e) {
            $erreur = "Erreur de base de données : " . $e->getMessage();
        }

        // 2. afficher la vue avec les données
        $this->afficher('menus', [
            'titre' => $titre,
            'menus' => $menus,
            'erreur' => $erreur,
        ]);
    }

    public function detail(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        $menu = null;


        try {
            $menuManager = new MenuManager(getPdo());
            $menu = $menuManager->findById($id);
        } catch (PDOException $e) {
            $erreur = "Erreur de base de données : " . $e->getMessage();
        }
        if ($menu === null) {
            http_response_code(404);
            $this->afficher('introuvable', [
                'titre' => 'Menu introuvable',
            ]);
        }
        
        $this->afficher('menu-detail', [
            'titre' => $menu->getTitre(),
            'menu' => $menu,
        ]);
    }
}
