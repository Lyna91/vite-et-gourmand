<?php

class UtilisateurManager
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        // range la connexion reçue dans la propriété $pdo de cet objet
        $this->pdo = $pdo;
    }
    public function emailExiste(string $email): bool //Elle répond par vrai ou faux à la question « cet e-mail a-t-il déjà un compte ? »
    {
        $requete = $this->pdo->prepare("SELECT COUNT(*) FROM utilisateur WHERE email = :email");
        $requete->execute(['email' => $email]);
        return $requete->fetchColumn() > 0;
    }

    public function creer(string $nom, string $prenom, string $email, string $telephone, string $adresse, string $cp, string $ville, string $motDePasse): void
    {
        $requete = $this->pdo->prepare("
        INSERT INTO utilisateur (nom, prenom, email, telephone, adresse, cp, ville, mot_de_passe, id_role)
        VALUES (:nom, :prenom, :email, :telephone, :adresse, :cp, :ville, :mot_de_passe, 3)
    ");  // requete preparée comme si j'ajoute un utilisateur dans creation.sql
        $requete->execute([
            'nom' => $nom,
            'prenom' => $prenom,
            'email' => $email,
            'telephone' => $telephone,
            'adresse' => $adresse,
            'cp' => $cp,
            'ville' => $ville,
            'mot_de_passe' => password_hash($motDePasse, PASSWORD_DEFAULT), //le mot de passe est haché avant d’arriver en base. Le mot de passe en clair n’est jamais enregistré
        ]);
    }

    public function trouverParMail(string $email): ?array
    {
        $requete = $this->pdo->prepare("
        SELECT id_utilisateur, prenom, mot_de_passe, id_role, actif
        FROM utilisateur
        WHERE email = :email
        ");
        $requete->execute(['email' => $email]);
        $ligne = $requete->fetch(PDO::FETCH_ASSOC);

        if ($ligne === false) {
            return null;
        }
        return $ligne;
    }

    // enregistre le jeton de réinitialisation, valable 1 heure
    public function enregistrerJeton(int $idUtilisateur, string $jetonHache): void
    {
        $requete = $this->pdo->prepare("
        UPDATE utilisateur
        SET token_reinitialisation = :jeton,
            token_expiration = DATE_ADD(NOW(), INTERVAL 1 HOUR)
        WHERE id_utilisateur = :id
        ");
        $requete->execute([
            'jeton' => $jetonHache,
            'id' => $idUtilisateur,
        ]);
    }

    // renvoie le compte qui a ce jeton encore valide, ou null
    public function trouverParJeton(string $jetonHache): ?array
    {
        $requete = $this->pdo->prepare("
        SELECT id_utilisateur
        FROM utilisateur
        WHERE token_reinitialisation = :jeton
          AND token_expiration > NOW()
    ");
        $requete->execute(['jeton' => $jetonHache]);
        $ligne = $requete->fetch(PDO::FETCH_ASSOC);

        if ($ligne === false) {
            return null;
        }
        return $ligne;
    }

    // enregistre le nouveau mot de passe haché et efface le jeton (usage unique)
    public function changerMotDePasse(int $idUtilisateur, string $motDePasse): void
    {
        $requete = $this->pdo->prepare("
        UPDATE utilisateur
        SET mot_de_passe = :mot_de_passe,
            token_reinitialisation = NULL,
            token_expiration = NULL
        WHERE id_utilisateur = :id
    ");
        $requete->execute([
            'mot_de_passe' => password_hash($motDePasse, PASSWORD_DEFAULT),
            'id' => $idUtilisateur,
        ]);
    }

    public function trouverParId(int $idUtilisateur): ?array
    {
        $requete = $this->pdo->prepare("
        SELECT id_utilisateur, nom, prenom, email, telephone, adresse, cp, ville
        FROM utilisateur
        WHERE id_utilisateur = :id_utilisateur
        ");
        $requete->execute(['id_utilisateur' => $idUtilisateur]);
        $ligne = $requete->fetch(PDO::FETCH_ASSOC);

        if ($ligne === false) {
            return null;
        }
        return $ligne;
    }
}
