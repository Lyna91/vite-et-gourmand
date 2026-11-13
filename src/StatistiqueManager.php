<?php

class StatistiqueManager
{
    private PDO $pdo;
    private MongoDB\Database $mongo;

    public function __construct(PDO $pdo, MongoDB\Database $mongo)
    {
        $this->pdo = $pdo;
        $this->mongo = $mongo;
    }

    public function synchroniser(): int
    {
        $requete = $this->pdo->query("
        SELECT commande.id_commande, commande.id_menu, menu.titre,
       commande.nbre_personne, commande.prix_menu, commande.montant_reduction,
       commande.date_commande,
       (SELECT statut FROM suivi_commande
        WHERE suivi_commande.id_commande = commande.id_commande
        ORDER BY id_suivi_commande DESC LIMIT 1) AS statut
        FROM commande
        JOIN menu ON commande.id_menu = menu.id_menu
        HAVING statut != 'annulée';");

        $commandes = $requete->fetchAll();

        // 2. Transformer chaque commande en document MongoDB
        $documents = [];
        foreach ($commandes as $commande) {
            $documents[] = [
                'id_commande' => (int) $commande['id_commande'],
                'id_menu' => (int) $commande['id_menu'],
                'titre_menu' => $commande['titre'],
                'nbre_personne' => (int) $commande['nbre_personne'],
                'chiffre_affaires' => (float) $commande['prix_menu'] - (float) $commande['montant_reduction'],
                'date_commande' => $commande['date_commande'],
            ];
        }

        // 3. Vider la collection, puis la remplir
        $collection = $this->mongo->selectCollection('commandes');
        $collection->deleteMany([]);

        if (!empty($documents)) {
            $collection->insertMany($documents);
        }

        return count($documents);
    }

    
}
