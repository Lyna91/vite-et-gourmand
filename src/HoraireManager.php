<?php

class HoraireManager
{

    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function findAll(): array
    {
        $requete = $this->pdo->query("SELECT id_horaire, jour, heure_ouverture, heure_fermeture FROM horaire ORDER BY id_horaire");
        $lignes = $requete->fetchAll(PDO::FETCH_ASSOC);
    
        $horaires = [];
        foreach ($lignes as $ligne) {
            $horaires[] = new Horaire($ligne['id_horaire'], $ligne['jour'], $ligne['heure_ouverture'], $ligne['heure_fermeture']);
        }

 
        return $horaires;
    }

    public function modifier(int $idHoraire, ?string $ouverture, ?string $fermeture): bool
    {
        $requete = $this->pdo->prepare ("
        UPDATE horaire SET heure_ouverture = :ouverture, heure_fermeture = :fermeture WHERE id_horaire = :id_horaire
        ");

        $requete->execute([
            'id_horaire' =>$idHoraire,
            'ouverture' => $ouverture,
            'fermeture' => $fermeture,
        ]);

        return $requete->rowCount() > 0;
    }
}
