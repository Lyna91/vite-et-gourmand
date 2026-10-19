<?php

class PlatManager
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        // range la connexion reçue dans la propriété $pdo de cet objet
        $this->pdo = $pdo;
    }

    public function findByMenu(int $idMenu): array
    {
        $requete = $this->pdo->prepare("
            SELECT plat.id_plat, plat.nom, plat.type
            FROM plat
            JOIN menu_plat ON plat.id_plat = menu_plat.id_plat
            WHERE menu_plat.id_menu = :id_menu
            ORDER BY FIELD(plat.type, 'Entrée', 'Plat', 'Dessert')

");
        $requete->execute(['id_menu' => $idMenu]);
        $lignes = $requete->fetchAll(PDO::FETCH_ASSOC);

        $plats = [];
        foreach ($lignes as $ligne) {
            $plats[] = new Plat($ligne['id_plat'], $ligne['nom'], $ligne['type']);
        }

        // renvoyer le tableau d'objets
        return $plats;
    }
}
