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

    public function trouverParUtilisateur(int $idUtilisateur): array
    {
        $requete = $this->pdo->prepare("
            SELECT commande.id_commande, commande.numero_commande, commande.date_commande,
                   commande.date_livraison, commande.heure_livraison, commande.nbre_personne,
                   commande.prix_menu, commande.montant_reduction, commande.frais_livraison,
                   menu.titre,
            (SELECT suivi_commande.statut
            FROM suivi_commande
            WHERE suivi_commande.id_commande = commande.id_commande
            ORDER BY suivi_commande.id_suivi_commande DESC
            LIMIT 1) AS statut,
            (SELECT COUNT(*)
            FROM avis
            WHERE avis.id_commande = commande.id_commande) AS nb_avis
            FROM commande
            JOIN menu ON commande.id_menu = menu.id_menu
            WHERE commande.id_utilisateur = :id_utilisateur
            ORDER BY commande.date_commande DESC, commande.id_commande DESC
        ");
        $requete->execute(['id_utilisateur' => $idUtilisateur]);
        return $requete->fetchAll(PDO::FETCH_ASSOC);
    }

    public function trouverToutes(string $statut = '', string $client = ''): array
    {
        $sql = "
            SELECT commande.id_commande, commande.numero_commande, commande.date_commande,
                   commande.date_livraison, commande.heure_livraison, commande.nbre_personne,
                   commande.prix_menu, commande.montant_reduction, commande.frais_livraison,
                   menu.titre,
                   utilisateur.nom, utilisateur.prenom,
                   utilisateur.email, utilisateur.telephone,
                   (SELECT suivi_commande.statut
                    FROM suivi_commande
                    WHERE suivi_commande.id_commande = commande.id_commande
                    ORDER BY suivi_commande.id_suivi_commande DESC
                    LIMIT 1) AS statut
            FROM commande
            JOIN menu ON commande.id_menu = menu.id_menu
            JOIN utilisateur ON commande.id_utilisateur = utilisateur.id_utilisateur
            WHERE 1 = 1
        ";
        $params = [];

        // filtre client : le nom, le prénom OU l'e-mail contient le texte tapé
        if ($client !== '') {
            $sql .= " AND (utilisateur.nom LIKE :client_nom
                       OR utilisateur.prenom LIKE :client_prenom
                       OR utilisateur.email LIKE :client_email)";
            $params['client_nom'] = '%' . $client . '%';
            $params['client_prenom'] = '%' . $client . '%';
            $params['client_email'] = '%' . $client . '%';
        }
        // filtre statut : sur la colonne calculée « statut » → HAVING
        if ($statut !== '') {
            $sql .= " HAVING statut = :statut";
            $params['statut'] = $statut;
        }

        $sql .= " ORDER BY commande.date_livraison ASC";

        $requete = $this->pdo->prepare($sql);
        $requete->execute($params);
        return $requete->fetchAll(PDO::FETCH_ASSOC);
    }

    public function annuler(int $idCommande, int $idUtilisateur): bool
    {
        // A. la commande existe, c'est la sienne, et elle est encore « en attente » ?
        $requete = $this->pdo->prepare("
            SELECT commande.id_menu, commande.nbre_personne,
                (SELECT suivi_commande.statut
                 FROM suivi_commande
                 WHERE suivi_commande.id_commande = commande.id_commande
                 ORDER BY suivi_commande.id_suivi_commande DESC
                 LIMIT 1) AS statut
            FROM commande
            WHERE commande.id_commande = :id_commande
              AND commande.id_utilisateur = :id_utilisateur
        ");
        $requete->execute([
            'id_commande' => $idCommande,
            'id_utilisateur' => $idUtilisateur,
        ]);
        $commande = $requete->fetch(PDO::FETCH_ASSOC);

        if ($commande === false || $commande['statut'] !== 'en attente') {
            return false;
        }

        $this->pdo->beginTransaction();
        try {
            $requete = $this->pdo->prepare("
        INSERT INTO suivi_commande (statut, date_statut, heure_statut, id_commande)
        VALUES (:statut, :date_statut, :heure_statut, :id_commande)");
            $requete->execute([
                'statut' => 'annulée',
                'date_statut' => date('Y-m-d'),
                'heure_statut' => date('H:i:s'),
                'id_commande' => $idCommande,
            ]);

            // 2. UPDATE du stock : stock + nbre_personne
            $requete = $this->pdo->prepare("
        UPDATE menu SET stock = stock + :quantite
        WHERE id_menu = :id_menu
        ");
            $requete->execute([
                'quantite' => $commande['nbre_personne'],
                'id_menu' => $commande['id_menu'],
            ]);

            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            error_log($e->getMessage());
            return false;
        }
    }

    public function modifier(int $idCommande, int $idUtilisateur, array $donnees, int $ancienNombre, int $idMenu): bool
    {
        $this->pdo->beginTransaction();
        try {
            // 1. mettre à jour la commande (seulement si c'est la sienne)
            $requete = $this->pdo->prepare("
                UPDATE commande
                SET nbre_personne = :nbre_personne, prix_menu = :prix_menu,
                    date_livraison = :date_livraison, heure_livraison = :heure_livraison,
                    adresse_livraison = :adresse_livraison, cp_livraison = :cp_livraison,
                    ville_livraison = :ville_livraison, frais_livraison = :frais_livraison,
                    montant_reduction = :montant_reduction
                WHERE id_commande = :id_commande AND id_utilisateur = :id_utilisateur
            ");
            $donnees['id_commande'] = $idCommande;
            $donnees['id_utilisateur'] = $idUtilisateur;
            $requete->execute($donnees);

            // 2. ajuster le stock de la différence (seulement si le nombre a changé)
            $difference = $donnees['nbre_personne'] - $ancienNombre;
            if ($difference !== 0) {
                $requete = $this->pdo->prepare("
                    UPDATE menu SET stock = stock - :difference
                    WHERE id_menu = :id_menu AND stock >= :difference_min
                ");
                $requete->execute([
                    'difference' => $difference,
                    'difference_min' => $difference,
                    'id_menu' => $idMenu,
                ]);
                if ($requete->rowCount() === 0) {
                    throw new Exception('Stock insuffisant pour modifier la commande.');
                }
            }

            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            error_log($e->getMessage());
            return false;
        }
    }

    public function trouverPourModification(int $idCommande, int $idUtilisateur): ?array
    {
        $requete = $this->pdo->prepare("
            SELECT commande.id_commande, commande.numero_commande, commande.nbre_personne,
            commande.date_livraison, commande.heure_livraison, commande.adresse_livraison,
            commande.cp_livraison, commande.ville_livraison, commande.frais_livraison,
            commande.id_menu,
                (SELECT suivi_commande.statut
                 FROM suivi_commande
                 WHERE suivi_commande.id_commande = commande.id_commande
                 ORDER BY suivi_commande.id_suivi_commande DESC
                 LIMIT 1) AS statut
            FROM commande
            WHERE commande.id_commande = :id_commande
              AND commande.id_utilisateur = :id_utilisateur
        ");
        $requete->execute([
            'id_commande' => $idCommande,
            'id_utilisateur' => $idUtilisateur,
        ]);

        $commande = $requete->fetch(PDO::FETCH_ASSOC);
        if ($commande === false) {
            return null;
        }
        return $commande;
    }

    public function trouverSuivi(int $idCommande): array
    {
        $requete = $this->pdo->prepare("
            SELECT statut, date_statut, heure_statut 
            FROM suivi_commande
            WHERE id_commande = :id_commande
            ORDER BY id_suivi_commande ASC
            ");

        $requete->execute([
            'id_commande' => $idCommande,
        ]);

        return $requete->fetchAll(PDO::FETCH_ASSOC);
    }

    public function ajouterStatut(int $idCommande, string $statut): void
    {
        $requete = $this->pdo->prepare("
        INSERT INTO suivi_commande (statut, date_statut, heure_statut, id_commande)
        VALUES (:statut, :date_statut, :heure_statut, :id_commande)");
        $requete->execute([
            'statut' => $statut,
            'id_commande' => $idCommande,
            'date_statut' => date('Y-m-d'),
            'heure_statut' => date('H:i:s'),
        ]);
    }
}
