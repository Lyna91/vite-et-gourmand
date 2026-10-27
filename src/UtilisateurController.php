<?php

class UtilisateurController extends Controller
{
    public function inscription(): void //: void veut dire « cette méthode ne renvoie rien ». Elle affiche, mais elle ne fait pas de return.
    {

        $erreurs = [];      // la liste des problèmes trouvés
        $succes = isset($_GET['succes']); //Se lit : « c’est un succès si l’adresse contient ?succes=1 »

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = trim($_POST['nom'] ?? '');
            $prenom = trim($_POST['prenom'] ?? '');
            $mail = trim($_POST['mail'] ?? '');
            $telephone = trim($_POST['telephone'] ?? '');
            $adresse = trim($_POST['adresse'] ?? '');
            $cp = trim($_POST['cp'] ?? '');
            $ville = trim($_POST['ville'] ?? '');
            $motDePasse = $_POST['motDePasse'] ?? '';
            $confirmation = $_POST['confirmation'] ?? '';

            // 2. vérifier
            if ($nom === '') {
                $erreurs[] = 'Le nom est obligatoire.';
            }
            if ($prenom === '') {
                $erreurs[] = 'Le prénom est obligatoire.';
            }
            if ($adresse === '') {
                $erreurs[] = 'Une adresse est obligatoire.';
            }
            if (!preg_match('/^[0-9]{5}$/', $cp)) {
                $erreurs[] = 'Le code postal est invalide, il doit comporter 5 chiffres.';
            }
            if ($ville === '') {
                $erreurs[] = 'La ville est obligatoire.';
            }
            if (!preg_match('/^0[0-9]{9}$/', $telephone)) {
                $erreurs[] = 'Le numéro de téléphone doit avoir 10 chiffres et commencer par 0.';
            }
            if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
                $erreurs[] = "L'adresse e-mail n'est pas valide.";
            }
            if ($motDePasse !== $confirmation) {
                $erreurs[] = 'Les 2 mots de passe sont différents.';
            }
            if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[^a-zA-Z0-9]).{10,}$/', $motDePasse)) {
                $erreurs[] = 'Le mot de passe doit contenir au moins 10 caractères, dont une majuscule, une minuscule, un chiffre et un caractère spécial.';
            }
            if (empty($erreurs)) {
                try {
                    // 1. le manager, qui parle à la base
                    $utilisateurManager = new UtilisateurManager(getPdo());
                    // mail déjà utilisé ? 
                    if ($utilisateurManager->emailExiste($mail)) {
                        $erreurs[] = 'Un compte est déjà existant avec cette adresse mail.';
                    } else {
                        //Sinon, on créée le compte
                        $utilisateurManager->creer($nom, $prenom, $mail, $telephone, $adresse, $cp, $ville, $motDePasse);
                        $mailer = new Mailer();
                        $mailer->envoyer(
                            $mail,
                            'Bienvenue chez Vite & Gourmand !',
                            "Bonjour $prenom,\n\nVotre compte a bien été crée. Vous pouvez désormais vous connecter et passer des commandes de menu. \n\nA bientôt, \nJulie et José"
                        );
                        header('Location: /inscription?succes=1'); //dit au navigateur « va plutôt à cette adresse ». C’est une redirection ;
                        exit; //arrête le PHP tout de suite : la redirection suffit, on n’affiche rien d’autre.
                    }
                } catch (PDOException $e) {
                    error_log($e->getMessage());
                    $erreurs[] = "L'inscription est momentanément indisponible, veuillez retenter plus tard.";
                }
            }
        }

        $this->afficher('inscription', [
            'titre' => 'Créer un compte',
            'erreurs' => $erreurs,
            'succes' => $succes,
        ]);
    }

    public function connexion(): void
    {
        $erreurs = [];      // la liste des problèmes trouvés

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $mail = trim($_POST['mail'] ?? '');
            $motDePasse = $_POST['motDePasse'] ?? '';

            // 2. vérifier
            if ($mail === '') {
                $erreurs[] = 'Mail invalide';
            }
            if ($motDePasse === '') {
                $erreurs[] = 'Mot de passe invalide';
            }
            if (empty($erreurs)) {
                try {
                    // 1. le manager, qui parle à la base
                    $utilisateurManager = new UtilisateurManager(getPdo());
                    $utilisateur = $utilisateurManager->trouverParMail($mail);

                    if ($utilisateur === null || !password_verify($motDePasse, $utilisateur['mot_de_passe'])) {
                        $erreurs[] = 'Mail ou mot de passe invalide.';
                    } elseif ((int) $utilisateur['actif'] !== 1) {
                        $erreurs[] = 'Ce compte est désactivé';
                    } else {
                        session_regenerate_id(true);
                        $_SESSION['id_utilisateur'] = $utilisateur['id_utilisateur'];
                        $_SESSION['prenom'] = $utilisateur['prenom'];
                        $_SESSION['id_role'] = $utilisateur['id_role'];
                        header('Location: /');
                        exit;
                    }
                } catch (PDOException $e) {
                    error_log($e->getMessage());
                    $erreurs[] = "La connexion est momentanément indisponible, veuillez retenter plus tard.";
                }
            }
        }

        $this->afficher('connexion', [
            'titre' => 'Se connecter',
            'erreurs' => $erreurs,
        ]);
    }

    public function deconnexion(): void
    {
        $_SESSION = [];

        session_destroy();

        $this->afficher('accueil', [
            'titre' => 'Accueil',
        ]);
        exit;

    }
}

//« S’il n’y a aucune erreur, je regarde dans la base si l’e-mail est déjà pris. Si oui, j’ajoute une erreur. 
//Sinon, je crée le compte et je note que c’est réussi. Et si la base plante, j’affiche un message neutre. »