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
            error_log($e->getMessage());
            $erreur = "Les menus sont momentanément indisponibles. Merci de réessayer plus tard.";
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
        $plats = [];


        try {
            $pdo = getPdo();
            $menuManager = new MenuManager($pdo);
            $menu = $menuManager->findById($id);
            $platManager = new PlatManager($pdo);
            $plats = $platManager->findByMenu($id);
        } catch (PDOException $e) {
            // base indisponible : $menu reste null, on affichera la page 404
        }
        if ($menu === null) {
            http_response_code(404);
            $this->afficher('introuvable', [
                'titre' => 'Menu introuvable',
            ]);
            return;
        }

        $this->afficher('menu-detail', [
            'titre' => $menu->getTitre(),
            'menu' => $menu,
            'plats' => $plats,
        ]);
    }
    // renvoie la liste des menus en JSON, pour le JavaScript (fetch)
    public function apiListe(): void
    {
        header('Content-Type: application/json; charset=utf-8');

        try {
            $prixMax = isset($_GET['prixMax']) && $_GET['prixMax'] !== '' ? (float) $_GET['prixMax'] : null;
            $menuManager = new MenuManager(getPdo());
            $menus = $menuManager->findByFiltres($prixMax);

            $donnees = [];
            foreach ($menus as $menu) {
                $donnees[] = $menu->toArray();
            }

            echo json_encode($donnees, JSON_UNESCAPED_UNICODE);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            http_response_code(500);
            echo json_encode(['erreur' => 'Menus indisponibles'], JSON_UNESCAPED_UNICODE);
        }
    }
}
