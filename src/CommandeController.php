<?php

class CommandeController extends Controller
{
    private const CODES_POSTAUX_BORDEAUX = ['33000', '33100', '33200', '33300', '33800'];

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
            // base indisponible : $menu reste null, affichage de la page 404 'menu introuvable'
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
            $heureLivraison = trim($_POST['heure_livraison'] ?? '');
            if (empty($heureLivraison)) {
                $erreurs[] = "Veuillez indiquez une heure de livraison";
            }
            $distance = (float) ($_POST['distance'] ?? 0);
            if (strtolower($ville) !== 'bordeaux' && $distance <= 0) {
                $erreurs[] = "Vous êtes en dehors de Bordeaux : veuillez indiquer la distance en kilomètres pour calculer les frais de livraison.";
            }
            if (strtolower($ville) === 'bordeaux' && !in_array($cp, self::CODES_POSTAUX_BORDEAUX)) {
                $erreurs[] = "Le code postal ne correspond à la ville de Bordeaux";
            }
            if ($menu->getStock() <= 0) {
                $erreurs[] = "Ce menu n'est plus disponible.";
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
