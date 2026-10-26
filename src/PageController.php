<?php

class PageController extends Controller
{


    public function accueil(): void //: void veut dire « cette méthode ne renvoie rien ». Elle affiche, mais elle ne fait pas de return.
    {
        // 1. préparer les données
        $titre = 'Accueil';

        $this->afficher('accueil', [
            'titre' => $titre,
        ]);
    }

    public function introuvable(): void
    {
        http_response_code(404);
        $this->afficher('introuvable', [
            'titre' => 'Page introuvable',
        ]);
    }

    public function mentionsLegales(): void
    {
        $titre = 'Mentions légales';

        $this->afficher('mentions-legales', [
            'titre' => $titre,
        ]);
    }

    public function cgv(): void
    {
        $titre = 'Conditions générales de vente';

        $this->afficher('cgv', [
            'titre' => $titre,
        ]);
    }
    public function contact(): void
{
    $erreurs = [];
    $envoye = isset($_GET['envoye']);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $mail = trim($_POST['mail'] ?? '');
        $sujet = trim($_POST['titre'] ?? '');
        $message = trim($_POST['message'] ?? '');

        if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
            $erreurs[] = "L'adresse e-mail n'est pas valide.";
        }
        if ($sujet === '') {
            $erreurs[] = 'Le titre est obligatoire.';
        }
        if ($message === '') {
            $erreurs[] = 'Le message est obligatoire.';
        }

        if (empty($erreurs)) {
            $mailer = new Mailer();
            $ok = $mailer->envoyer(getenv('MAIL_CONTACT'), 'Contact : ' . $sujet, $message, $mail);

            if ($ok) {
                header('Location: /contact?envoye=1');
                exit;
            }
            $erreurs[] = "Le message n'a pas pu être envoyé. Merci de réessayer plus tard.";
        }
    }   // ← fin du if POST : l'envoi est bien DEDANS

    $this->afficher('contact', [
        'titre' => 'Nous contacter',
        'erreurs' => $erreurs,
        'envoye' => $envoye,
    ]);
}
}
