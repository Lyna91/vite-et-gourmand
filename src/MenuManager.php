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

        // 1. la requête : les colonnes de menu, plus le régime et le thème
        $requete = $this->pdo->query("
            SELECT menu.*,
                regime.libelle AS regime,
                theme.libelle AS theme
            FROM menu
            JOIN regime ON menu.id_regime = regime.id_regime
            JOIN theme ON menu.id_theme = theme.id_theme
            ORDER BY menu.id_menu
");
        $lignes = $requete->fetchAll(PDO::FETCH_ASSOC);


        // 2. transformer chaque ligne en objet Menu
        $menus = [];
        foreach ($lignes as $ligne) {
            $menus[] = new Menu($ligne['id_menu'], $ligne['titre'], $ligne['description'], $ligne['prix_par_personne'], $ligne['conditions'], $ligne['stock'], $ligne['nbre_personne_mini'], $ligne['regime'], $ligne['theme']);
        }

        // 3. renvoyer le tableau d'objets
        return $menus;
    }

    public function findById(int $id): ?Menu
    {
        $requete = $this->pdo->prepare("
            SELECT menu.*,
                regime.libelle AS regime,
                theme.libelle AS theme
            FROM menu
            JOIN regime ON menu.id_regime = regime.id_regime
            JOIN theme ON menu.id_theme = theme.id_theme
            WHERE menu.id_menu = :id
");
        $requete->execute(['id' => $id]);
        $ligne = $requete->fetch(PDO::FETCH_ASSOC);

        if ($ligne === false) {
            return null;
        }
        return new Menu($ligne['id_menu'], $ligne['titre'], $ligne['description'], $ligne['prix_par_personne'], $ligne['conditions'], $ligne['stock'], $ligne['nbre_personne_mini'], $ligne['regime'], $ligne['theme']);
    }
}
