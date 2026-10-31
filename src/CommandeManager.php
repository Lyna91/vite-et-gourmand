<?php

class CommandeManager
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        // range la connexion reçue dans la propriété $pdo de cet objet
        $this->pdo = $pdo;
    }

    public function creer(array $commande): int
    {
        $this->pdo->beginTransaction();     // 1. « je commence, rien n'est définitif »

        try {
            $requete = $this->pdo->prepare("
        INSERT INTO commande (numero_commande, nbre_personne, prix_menu, date_commande, date_livraison, heure_livraison, adresse_livraison, cp_livraison, ville_livraison, frais_livraison, montant_reduction, id_utilisateur, id_menu)
        VALUES (:numero_commande, :nbre_personne, :prix_menu, :date_commande, :date_livraison, :heure_livraison, :adresse_livraison, :cp_livraison, :ville_livraison, :frais_livraison, :montant_reduction, :id_utilisateur, :id_menu)");
            $requete->execute($commande);

            $idCommande = (int) $this->pdo->lastInsertId();

            $requete = $this->pdo->prepare("
        INSERT INTO suivi_commande (statut, date_statut, heure_statut, id_commande)
        VALUES (:statut, :date_statut, :heure_statut, :id_commande)");
            $requete->execute([
                'statut' => 'en attente',
                'id_commande' => $idCommande,
                'date_statut' => date('Y-m-d'),
                'heure_statut' => date('H:i:s'),
            ]);

            $requete = $this->pdo->prepare("
        UPDATE menu SET stock = stock - :quantite
        WHERE id_menu = :id_menu AND stock >= :quantite_min
        ");
            $requete->execute([
                'quantite' => $commande['nbre_personne'],
                'quantite_min' => $commande['nbre_personne'],
                'id_menu' => $commande['id_menu'],
            ]);

            if ($requete->rowCount() === 0) {
                throw new Exception('Stock insuffisant pour ce menu.');
            }

            $this->pdo->commit();           // 2. « tout s'est bien passé, je valide »
            return $idCommande;
        } catch (Exception $e) {
            $this->pdo->rollBack();         // 3. « problème : j'annule TOUT »
            throw $e;                       // et je signale l'erreur au contrôleur
        }
    }
}
