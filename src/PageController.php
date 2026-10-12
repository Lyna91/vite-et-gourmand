<?php

class PageController {


   public function accueil(): void //: void veut dire « cette méthode ne renvoie rien ». Elle affiche, mais elle ne fait pas de return.
    {
        // 1. préparer les données
        $titre = 'Accueil';
        
        require __DIR__ . '/../views/accueil.php';
    }

    public function introuvable(): void
        {
            http_response_code(404);
            $titre = 'Page introuvable';

        require __DIR__ . '/../views/introuvable.php';
        }
}