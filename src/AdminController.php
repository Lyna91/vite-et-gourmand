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

    public function synchroniserStatistiques(): void
    {
        $this->exigerRole([1]);

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /admin/statistiques');
            exit;
        }

        $statistiqueManager = new StatistiqueManager(getPdo(), getMongo());
        $nombre = $statistiqueManager->synchroniser();
        header('Location: /admin/statistiques?synchronise=' . ($nombre));
        exit;
    }

    public function statistiques(): void
    {
        $this->exigerRole([1]);

        $statistiqueManager = new StatistiqueManager(getPdo(), getMongo());
        $menuManager = new MenuManager(getPdo());
        $menus = $menuManager->findAll();
        $erreur = '';

        // 1. Lire les filtres (GET), vides = pas de filtre
        $idMenu = ($_GET['menu'] ?? '') !== '' ? (int) $_GET['menu'] : null;
        $dateDebut = ($_GET['debut'] ?? '') !== '' ? $_GET['debut'] : null;
        $dateFin = ($_GET['fin'] ?? '') !== '' ? $_GET['fin'] : null;

        // 2. Vérifier les filtres → À TOI
        $idsMenus = array_map(fn($menu) => $menu->getId(), $menus);
        if ($idMenu !== null && !in_array($idMenu, $idsMenus)) {
            $erreur = "Le menu choisi n'existe pas.";
            $idMenu = null;
        }
        if ($dateDebut !== null && $dateFin !== null && $dateFin < $dateDebut) {
            $erreur = "La date de fin est antérieure à la date de début.";
            $dateDebut = null;
            $dateFin = null;
        }


        // 3. Récupérer les statistiques
        $commandesParMenu = $statistiqueManager->commandesParMenu();
        $chiffresAffaires = $statistiqueManager->chiffreAffairesParMenu($idMenu, $dateDebut, $dateFin);

        $this->afficher('admin/statistiques', [
            'titre' => 'Statistiques',
            'menus' => $menus,
            'commandesParMenu' => $commandesParMenu,
            'chiffresAffaires' => $chiffresAffaires,
            'idMenu' => $idMenu,
            'dateDebut' => $dateDebut,
            'dateFin' => $dateFin,
            'erreur' => $erreur,
            'synchronise' => $_GET['synchronise'] ?? null,
        ]);
    }
}
