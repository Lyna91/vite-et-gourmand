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
            'cree' => isset($_GET['cree']),
            'modifie' => isset($_GET['modifie']),
            'erreur' => isset($_GET['erreur']),
        ]);
    }

    public function creerEmploye(): void
    {
        $this->exigerRole([1]);

        $utilisateurManager = new UtilisateurManager(getPdo());
        $erreurs = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = trim($_POST['nom'] ?? '');
            $prenom = trim($_POST['prenom'] ?? '');
            $mail = trim($_POST['email'] ?? '');
            $motDePasse = $_POST['motDePasse'] ?? '';

            if (empty($nom)) {
                $erreurs[] = "Le nom est obligatoire";
            }
            if (empty($prenom)) {
                $erreurs[] = "Le prénom est obligatoire";
            }
            if (empty($mail)) {
                $erreurs[] = "Le mail est obligatoire";
            }
            if ($utilisateurManager->emailExiste($mail)) {
                $erreurs[] = 'Un compte est déjà existant avec cette adresse mail.';
            }
            if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
                $erreurs[] = "Le mail n'est pas au bon format";
            }
            if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[^a-zA-Z0-9]).{10,}$/', $motDePasse)) {
                $erreurs[] = 'Le mot de passe doit contenir au moins 10 caractères, dont une majuscule, une minuscule, un chiffre et un caractère spécial.';
            }
            if (empty($erreurs)) {
                try {
                    $utilisateurManager->creerEmploye($nom, $prenom, $mail, $motDePasse);
                    $mailer = new Mailer();
                    $mailer->envoyer(
                        $mail,
                        'Votre compte employé Vite & Gourmand',
                        "Bonjour $prenom,\n\n"
                            . "Un compte employé a été créé pour vous sur Vite & Gourmand.\n"
                            . "Votre identifiant de connexion est votre adresse e-mail : $mail\n\n"
                            . "Pour des raisons de sécurité, votre mot de passe ne vous est pas envoyé par mail. "
                            . "Merci de vous rapprocher de l'administrateur pour l'obtenir.\n\n"
                            . "À bientôt,\nJulie et José"
                    );
                    header('Location: /admin/employes?cree=1');
                    exit;
                } catch (PDOException $e) {
                    error_log($e->getMessage());
                    $erreurs[] = "La création du compte est momentanément indisponible, veuillez retenter plus tard.";
                }
            }
        }
        $this->afficher('admin/creer-employe', [
            'titre' => 'Créer un employé',
            'erreurs' => $erreurs,
        ]);
    }

    public function changerActifEmploye(): void
    {
        $this->exigerRole([1]);

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /admin/employes');
            exit;
        }

        $idUtilisateur = (int) ($_POST['id_utilisateur'] ?? 0);
        $actif = (int) ($_POST['actif'] ?? -1);

        if (!in_array($actif, [0, 1], true)) {
            header('Location: /admin/employes?erreur=1');
            exit;
        }
        $utilisateurManager = new UtilisateurManager(getPdo());
        if ($utilisateurManager->changerActif($idUtilisateur, $actif)) {
            header('Location: /admin/employes?modifie=1');
        } else {
            header('Location: /admin/employes?erreur=1');
        }
        exit;
    }
}
