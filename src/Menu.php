<?php

class Menu
{
    // 1. Les PROPRIÉTÉS : les informations que contient chaque plat
    private int $id;
    private string $titre;
    private string $description;
    private float $prixParPersonne;

    // 2. Le CONSTRUCTEUR : appelé automatiquement à la création, il remplit les propriétés
    public function __construct(int $id, string $titre, string $description, float $prixParPersonne)
    {
        $this->id = $id;
        $this->titre = $titre;
        $this->description = $description;
        $this->prixParPersonne = $prixParPersonne;
    }

    // 3. Les GETTERS : des fonctions pour LIRE les propriétés depuis l'extérieur
    public function getId(): int
    {
        return $this->id;
    }

    public function getTitre(): string
    {
        return $this->titre;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getPrixParPersonne(): float
    {
        return $this->prixParPersonne;
    }
}
