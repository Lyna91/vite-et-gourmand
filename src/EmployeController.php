<?php

class EmployeController extends Controller
{
    public function accueil(): void
    {
        $this->exigerRole([1, 2]);      // admin et employé seulement

        $commandeManager = new CommandeManager(getPdo());
        $commandes = $commandeManager->trouverToutes();

        $this->afficher('employe/accueil', [
            'titre' => 'Espace employé',
            'commandes' => $commandes,
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
}
