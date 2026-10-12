<?php

class Horaire
{

    private int $id;
    private string $jour;
    private ?string $heureOuverture;
    private ?string $heureFermeture;




    public function __construct(int $id, string $jour, ?string $heureOuverture, ?string $heureFermeture)
    {
        $this->id = $id;
        $this->jour = $jour;
        $this->heureOuverture = $heureOuverture;
        $this->heureFermeture = $heureFermeture;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getJour(): string
    {
        return $this->jour;
    }

    public function getHeureOuverture(): ?string
    {
        return $this->heureOuverture;
    }

    public function getHeureFermeture(): ?string
    {
        return $this->heureFermeture;
    }
}
