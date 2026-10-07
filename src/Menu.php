<?php

class Menu
{
    // 1. Les PROPRIÉTÉS : les informations que contient chaque plat
    private int $id;
    private string $titre;
    private string $description;
    private float $prix_par_personne;

    // 2. Le CONSTRUCTEUR : appelé automatiquement à la création, il remplit les propriétés
    public function __construct(int $id, string $titre, string $description, float $prix_par_personne)
    {
        $this->id = $id;
        $this->titre = $titre;
        $this->description = $description;
        $this->prix_par_personne = $prix_par_personne;
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
        return $this->prix_par_personne;
    }
}

$menu = new Menu(1, 'Pour la vie', 'Un menu dédié aux amoureux du jour, composé avec amour', 60.00);   // new = fabrique un objet avec le moule
