<?php

class PageController extends Controller
{


    public function accueil(): void //: void veut dire « cette méthode ne renvoie rien ». Elle affiche, mais elle ne fait pas de return.
    {
        // 1. préparer les données
        $titre = 'Accueil';

        $this->afficher('accueil', [
            'titre' => $titre,
        ]);
    }

    public function introuvable(): void
    {
        http_response_code(404);
        $this->afficher('introuvable', [
            'titre' => 'Page introuvable',
        ]);
    }

    public function mentionsLegales(): void
    {
        $titre = 'Mentions légales';

        $this->afficher('mentions-legales', [
            'titre' => $titre,
        ]);
    }

    public function cgv(): void
    {
        $titre = 'Conditions générales de vente';

        $this->afficher('cgv', [
            'titre' => $titre,
        ]);
    }
}
