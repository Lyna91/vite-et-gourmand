<?php

class AvisManager
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        // range la connexion reçue dans la propriété $pdo de cet objet
        $this->pdo = $pdo;
    }
    //est-ce qu’un avis existe déjà pour cette commande ?
    public function existePourCommande(int $idCommande): bool
    {
        $requete = $this->pdo->prepare("SELECT COUNT(*) FROM avis WHERE id_commande = :id_commande");
        $requete->execute(['id_commande' => $idCommande]);
        return $requete->fetchColumn() > 0;
    }

    // on enregistre l'avis
    public function creer(int $idCommande, int $note, string $commentaire): void
    {
        $requete = $this->pdo->prepare("
        INSERT INTO avis (description_avis, note, statut, date_avis, id_commande)
        VALUES (:description_avis, :note, :statut, :date_avis, :id_commande)");
        $requete->execute([
            'description_avis' => $commentaire,
            'note' => $note,
            'statut' => 'en attente',
            'date_avis' => date('Y-m-d'),
            'id_commande' => $idCommande,
        ]);
    }

    public function trouverEnAttente(): array
    {
        $requete = $this->pdo->prepare("
        SELECT avis.id_avis, avis.note, avis.description_avis, avis.date_avis,
        commande.numero_commande, utilisateur.prenom
        FROM avis
        JOIN commande ON avis.id_commande = commande.id_commande
        JOIN utilisateur ON commande.id_utilisateur = utilisateur.id_utilisateur
        WHERE avis.statut = :statut
        ORDER BY avis.date_avis ASC");

        $requete->execute([
            'statut' => 'en attente',
        ]);
        return $requete->fetchAll();
    }
}
