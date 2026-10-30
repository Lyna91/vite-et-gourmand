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
        $id = (int) ($_POST['menu'] ?? $_GET['menu'] ?? 0);
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

        $erreurs = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $adresse = trim($_POST['adresse'] ?? '');
            if (empty($adresse)) {
                $erreurs[] = "L'adresse est obligatoire.";
            }
            $ville = trim($_POST['ville'] ?? '');
            if (empty($ville)) {
                $erreurs[] = "La ville est obligatoire.";
            }
            $cp = trim($_POST['cp'] ?? '');
            if (!preg_match('/^[0-9]{5}$/', $cp)) {
                $erreurs[] = "Le code postal est incorrect";
            }
            $nombre = (int) ($_POST['nbre_personne'] ?? 0);
            if ($nombre < $menu->getNbrePersonneMini()) {
                $erreurs[] = "Ce menu est prévu pour au moins " . $menu->getNbrePersonneMini() . " personnes.";
            }
            $dateLivraison = trim($_POST['date_livraison'] ?? '');
            if ($dateLivraison <= date('Y-m-d')) {
                $erreurs[] = "La date doit être postérieure à aujourd'hui";
            }
        }
        $this->afficher('commande', [
            'titre' => 'Commander',
            'menu' => $menu,
            'utilisateur' => $utilisateur,
            'erreurs' => $erreurs
        ]);
    }
}
