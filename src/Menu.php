<?php

class Menu
{
    // 1. Les PROPRIÉTÉS : les informations que contient chaque menu
    private int $id;
    private string $titre;
    private string $description;
    private float $prixParPersonne;
    private string $conditions;
    private int $stock;
    private int $nbrePersonneMini;
    private string $regime;
    private string $theme;

    // 2. Le CONSTRUCTEUR : appelé automatiquement à la création, il remplit les propriétés
    public function __construct(int $id, string $titre, string $description, float $prixParPersonne, string $conditions, int $stock, int $nbrePersonneMini, string $regime, string $theme)
    {
        $this->id = $id;
        $this->titre = $titre;
        $this->description = $description;
        $this->prixParPersonne = $prixParPersonne;
        $this->conditions = $conditions;
        $this->stock = $stock;
        $this->nbrePersonneMini = $nbrePersonneMini;
        $this->regime = $regime;
        $this->theme = $theme;
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

    public function getConditions(): string
    {
        return $this->conditions;
    }

    public function getStock(): int
    {
        return $this->stock;
    }

    public function getNbrePersonneMini(): int
    {
        return $this->nbrePersonneMini;
    }

    public function getRegime(): string
    {
        return $this->regime;
    }
    
    public function getTheme(): string
    {
        return $this->theme;
    }
}
