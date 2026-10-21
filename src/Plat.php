<?php

class Plat
{
    private int $id;
    private string $nom;
    private string $type;
    /** @var string[] la liste des noms d'allergènes */
    private array $allergenes;

    public function __construct(int $id, string $nom, string $type, array $allergenes)
    {
        $this->id = $id;
        $this->nom = $nom;
        $this->type = $type;
        $this->allergenes = $allergenes;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function getType(): string
    {
        return $this->type;
    }
    
    public function getAllergenes(): array
    {
        return $this->allergenes;
    }
}
