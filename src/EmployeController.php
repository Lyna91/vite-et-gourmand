<?php

class EmployeController extends Controller
{
    public function accueil(): void
    {
        $this->exigerRole([1, 2]);      // admin et employé seulement

        $this->afficher('employe/accueil', [
            'titre' => 'Espace employé',
        ]);
    }
}