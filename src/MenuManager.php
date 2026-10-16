<?php

class MenuManager
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        // range la connexion reçue dans la propriété $pdo de cet objet
        $this->pdo = $pdo;
    }

    public function findAll(): array
    {

        // 1. la requête : les 4 colonnes dont le constructeur de Menu a besoin
        $requete = $this->pdo->query("SELECT id_menu, titre, description, prix_par_personne FROM menu");
        $lignes = $requete->fetchAll(PDO::FETCH_ASSOC);


        // 2. transformer chaque ligne en objet Menu
        $menus = [];
        foreach ($lignes as $ligne) {
            $menus[] = new Menu($ligne['id_menu'], $ligne['titre'], $ligne['description'], $ligne['prix_par_personne']);
        }

        // 3. renvoyer le tableau d'objets
        return $menus;
    }

    public function findById(int $id): ?Menu
    {
        $requete = $this->pdo->prepare("SELECT id_menu, titre, description, prix_par_personne FROM menu WHERE id_menu = :id");
        $requete->execute(['id' => $id]);
        $ligne = $requete->fetch(PDO::FETCH_ASSOC);

        if ($ligne === false) {
            return null;
        }
        return new Menu($ligne['id_menu'], $ligne['titre'], $ligne['description'], $ligne['prix_par_personne']);
    }
}
