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

    public function accueil(): void
    {
        $this->exigerRole([1, 2]);      // admin et employé seulement

        $commandeManager = new CommandeManager(getPdo());
        $commandes = $commandeManager->trouverToutes();

        $statutModifie = isset($_GET['statut_modifie']);
        $erreur = isset($_GET['erreur']);


        $this->afficher('employe/accueil', [
            'titre' => 'Espace employé',
            'commandes' => $commandes,
            'statutModifie' => $statutModifie,
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
}
