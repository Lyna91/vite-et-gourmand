<?php

class CommandeController extends Controller
{
    private const CODES_POSTAUX_BORDEAUX = ['33000', '33100', '33200', '33300', '33800'];
    private const TAUX_REMISE = 0.10;
    private const SEUIL_REMISE = 5;
    private const FRAIS_LIVRAISON = 5;
    private const PRIX_KM = 0.59;

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
            /*if ($menu->getStock() < $nombre) {
                $erreurs[] = "Il ne reste que " . $menu->getStock() . " menus disponibles.";
            }*/
            if (empty($erreurs)) {
                $totalMenus =  round($menu->getPrixParPersonne() * $nombre, 2);           //total menu égal au prix par personne * le nombre
                $remise = 0;                                                    // pour la remise
                if ($nombre >= $menu->getNbrePersonneMini() + self::SEUIL_REMISE) { //si le nombre est sup ou = au nbre de pers mini + nombre pour la remise (ici 5)
                    $remise = round($totalMenus * self::TAUX_REMISE, 2);                  // la remise est = totalMenu * taux de remise (ici 10%)
                }
                $livraison = 0;                                                 // pour la livraison
                if (strtolower($ville) !== 'bordeaux') {                        // si la ville n'est pas bordeaux
                    $livraison = round(self::FRAIS_LIVRAISON + self::PRIX_KM * $distance, 2);
                }
                $total = round($totalMenus - $remise + $livraison, 2);
                $numero = 'CMD-' . date('Ymd') . '-' . strtoupper(bin2hex(random_bytes(3)));
                $commande = [
                    'numero_commande' => $numero,
                    'nbre_personne' => $nombre,
                    'prix_menu' => $totalMenus,
                    'date_commande' => date('Y-m-d'),
                    'date_livraison' => $dateLivraison,
                    'heure_livraison' => $heureLivraison,
                    'adresse_livraison' => $adresse,
                    'cp_livraison' => $cp,
                    'ville_livraison' => $ville,
                    'frais_livraison' => $livraison,
                    'montant_reduction' => $remise,
                    'id_utilisateur' => $_SESSION['id_utilisateur'],
                    'id_menu' => $menu->getId(),
                ];
                try {
                    $commandeManager = new CommandeManager(getPdo());
                    $idCommande = $commandeManager->creer($commande);
                    var_dump($idCommande);
                } catch (Exception $e) {
                    error_log($e->getMessage());
                    $erreurs[] = "Votre commande n'a pas pu être enregistrée. Veuillez réessayer.";
                }
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
