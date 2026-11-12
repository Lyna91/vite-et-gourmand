<?php

class AdminController extends Controller
{
    public function employes(): void
    {
        $this->exigerRole([1]); //admin seulement

        $utilisateurManager = new UtilisateurManager(getPdo());
        $employes = $utilisateurManager->trouverEmployes();

        $this->afficher('admin/employes', [
            'titre' => 'Gérer les employés',
            'employes' => $employes,
        ]);
    }
}