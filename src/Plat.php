<?php

class Plat
{
    private int $id;
    private string $nom;
    private string $type;

    public function __construct(int $id, string $nom, string $type)
    {
        $this->id = $id;
        $this->nom = $nom;
        $this->type = $type;
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
}
