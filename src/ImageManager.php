<?php

class ImageManager
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
            SELECT id_image, chemin, texte_image 
            FROM image 
            WHERE id_menu = :id_menu 
            ORDER BY id_image

");
        $requete->execute(['id_menu' => $idMenu]);
        $lignes = $requete->fetchAll(PDO::FETCH_ASSOC);

        $images = [];
        foreach ($lignes as $ligne) {
            $images[] = new Image($ligne['id_image'], $ligne['chemin'], $ligne['texte_image']);
        }
        // renvoyer le tableau d'objets
        return $images;
    }
}
