<?php


class EmployeController extends Controller
{

    private const STATUTS_AUTORISES = [
        'accepté',
        'en préparation',
        'en cours de livraison',
        'livré',
        'en attente du retour de matériel',
        'terminée',
    ];

    private const MODES_CONTACT = ['GSM', 'mail'];

    public function accueil(): void
    {
        $this->exigerRole([1, 2]);      // admin et employé seulement

        $commandeManager = new CommandeManager(getPdo());
        $commandes = $commandeManager->trouverToutes();

        $statutModifie = isset($_GET['statut_modifie']);
        $annulee = isset($_GET['annulee']);
        $erreur = isset($_GET['erreur']);


        $this->afficher('employe/accueil', [
            'titre' => 'Espace employé',
            'commandes' => $commandes,
            'statutModifie' => $statutModifie,
            'annulee' => $annulee,
            'erreur' => $erreur,
        ]);
    }

    public function apiCommandes(): void
    {
        // 1. réservé aux employés et à l'admin
        $this->exigerRole([1, 2]);

        // 2. les filtres, lus dans l'adresse
        $statut = trim($_GET['statut'] ?? '');
        $client = trim($_GET['client'] ?? '');

        // 3. les commandes filtrées
        $commandeManager = new CommandeManager(getPdo());
        $commandes = $commandeManager->trouverToutes($statut, $client);

        // 4. la réponse en JSON
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($commandes, JSON_UNESCAPED_UNICODE);
    }



    public function changerStatut(): void
    {

        $this->exigerRole([1, 2]);

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /employe');
            exit;
        }

        $idCommande = (int) ($_POST['id_commande'] ?? 0);
        $statut = trim($_POST['statut'] ?? '');

        if (!in_array($statut, self::STATUTS_AUTORISES)) {
            header('Location: /employe?erreur=1');
            exit;
        }

        $commandeManager = new CommandeManager(getPdo());
        $commandeManager->ajouterStatut($idCommande, $statut);

        header('Location: /employe?statut_modifie=1');
    }

    public function annulerCommande(): void
    {
        $this->exigerRole([1, 2]);

        // l'id arrive dans l'adresse (?id=…), puis dans le champ caché (POST)
        $idCommande = (int) ($_POST['id_commande'] ?? $_GET['id'] ?? 0);
        $erreurs = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $modeContact = trim($_POST['mode_contact'] ?? '');
            $motif = trim($_POST['motif'] ?? '');

            if (!in_array($modeContact, self::MODES_CONTACT)) {                                // le mode n'est pas dans la liste blanche
                $erreurs[] = "Indiquez comment le client a été contacté (GSM ou mail).";
            }
            if (empty($motif)) {                                // le motif est vide
                $erreurs[] = "Le motif d'annulation est obligatoire.";
            }

            if (empty($erreurs)) {
                $commandeManager = new CommandeManager(getPdo());
                $annulee = $commandeManager->annulerParEmploye($idCommande, $modeContact, $motif);
                if ($annulee) {
                    header('Location: /employe?annulee=1');
                    exit;
                }
                $erreurs[] = "Cette commande ne peut pas être annulée (déjà annulée ou terminée).";
            }
        }

        $this->afficher('employe/annuler', [
            'titre' => 'Annuler une commande',
            'idCommande' => $idCommande,
            'erreurs' => $erreurs,
        ]);
    }
}
