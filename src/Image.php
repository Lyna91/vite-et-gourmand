<?php

class Image
{
    private int $id;
    private string $chemin;
    private string $texte;
    
    public function __construct(int $id, string $chemin, string $texte)
    {
        $this->id = $id;
        $this->chemin = $chemin;
        $this->texte = $texte;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getChemin(): string
    {
        return $this->chemin;
    }

    public function getTexte(): string
    {
        return $this->texte;
    }
}
