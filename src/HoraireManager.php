<?php

class HoraireManager
{

    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        // range la connexion reçue dans la propriété $pdo de cet objet
        $this->pdo = $pdo;
    }

    public function findAll(): array
    {
        // 1. la requête : les 4 colonnes dont le constructeur de horaire a besoin et donner un ordre via id_horaire (pour avoir du lundi au dimanche)
        $requete = $this->pdo->query("SELECT id_horaire, jour, heure_ouverture, heure_fermeture FROM horaire ORDER BY id_horaire");
        $lignes = $requete->fetchAll(PDO::FETCH_ASSOC);
        // 2. transformer chaque ligne en objet 
        $horaires = [];
        foreach ($lignes as $ligne) {
            $horaires[] = new Horaire($ligne['id_horaire'], $ligne['jour'], $ligne['heure_ouverture'], $ligne['heure_fermeture']);
        }

        // 3. renvoyer le tableau d'objets
        return $horaires;
    }
}
