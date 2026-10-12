<?php

class MenuController
{
    public function liste(): void //: void veut dire « cette méthode ne renvoie rien ». Elle affiche, mais elle ne fait pas de return.
    {
        // 1. préparer les données
        $menus = [];
        $erreur = null;

        try {
            $menuManager = new MenuManager(getPdo());
            $menus = $menuManager->findAll();
        } catch (PDOException $e) {
            $erreur = "Erreur de base de données : " . $e->getMessage();
        }

        // 2. afficher : charger la vue
        require __DIR__ . '/../views/menus.php';
    }
}
