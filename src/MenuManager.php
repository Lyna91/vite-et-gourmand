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

    // tous les menus, avec des filtres facultatifs
    public function findByFiltres(?float $prixMin, ?float $prixMax, ?int $nbrePersonnes): array
    {
        $sql = "
        SELECT menu.*, regime.libelle AS regime, theme.libelle AS theme
        FROM menu
        JOIN regime ON menu.id_regime = regime.id_regime
        JOIN theme ON menu.id_theme = theme.id_theme
        WHERE 1 = 1
    ";
        $parametres = [];

        if ($prixMin !== null) {
            $sql .= " AND menu.prix_par_personne >= :prixMin";
            $parametres['prixMin'] = $prixMin;
        }
        if ($prixMax !== null) {
            $sql .= " AND menu.prix_par_personne <= :prixMax";
            $parametres['prixMax'] = $prixMax;
        }
        if ($nbrePersonnes !== null) {
            $sql .= " AND menu.nbre_personne_mini <= :nbrePersonnes";
            $parametres['nbrePersonnes'] = $nbrePersonnes;
        }

        $sql .= " ORDER BY menu.id_menu";

        $requete = $this->pdo->prepare($sql);
        $requete->execute($parametres);
        $lignes = $requete->fetchAll(PDO::FETCH_ASSOC);

        $menus = [];
        foreach ($lignes as $ligne) {
            $menus[] = new Menu($ligne['id_menu'], $ligne['titre'], $ligne['description'], $ligne['prix_par_personne'], $ligne['conditions'], $ligne['stock'], $ligne['nbre_personne_mini'], $ligne['regime'], $ligne['theme']);
        }
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
