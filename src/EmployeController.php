<?php


class EmployeController extends Controller
{

    private const STATUTS_AUTORISES = [
        'accepté',
        'en préparation',
        'en cours de livraison',
        'livré',
        'en attente du retour de matériel',
        'terminée',
    ];

    private const STATUTS_AVIS = ['validé', 'refusé'];

    private const MODES_CONTACT = ['GSM', 'mail'];

    private const TYPES_PLAT = ['Entrée', 'Plat', 'Dessert'];

    public function accueil(): void
    {
        $this->exigerRole([1, 2]);      // admin et employé seulement

        $commandeManager = new CommandeManager(getPdo());
        $commandes = $commandeManager->trouverToutes();

        $statutModifie = isset($_GET['statut_modifie']);
        $annulee = isset($_GET['annulee']);
        $erreur = isset($_GET['erreur']);


        $this->afficher('employe/accueil', [
            'titre' => 'Espace employé',
            'commandes' => $commandes,
            'statutModifie' => $statutModifie,
            'annulee' => $annulee,
            'erreur' => $erreur,
        ]);
    }

    public function apiCommandes(): void
    {
        // 1. réservé aux employés et à l'admin
        $this->exigerRole([1, 2]);

        // 2. les filtres, lus dans l'adresse
        $statut = trim($_GET['statut'] ?? '');
        $client = trim($_GET['client'] ?? '');

        // 3. les commandes filtrées
        $commandeManager = new CommandeManager(getPdo());
        $commandes = $commandeManager->trouverToutes($statut, $client);

        // 4. la réponse en JSON
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($commandes, JSON_UNESCAPED_UNICODE);
    }



    public function changerStatut(): void
    {

        $this->exigerRole([1, 2]);

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /employe');
            exit;
        }

        $idCommande = (int) ($_POST['id_commande'] ?? 0);
        $statut = trim($_POST['statut'] ?? '');

        if (!in_array($statut, self::STATUTS_AUTORISES)) {
            header('Location: /employe?erreur=1');
            exit;
        }

        $commandeManager = new CommandeManager(getPdo());
        $commandeManager->ajouterStatut($idCommande, $statut);

        //les mails au client
        $client = $commandeManager->trouverClient($idCommande);

        if ($client !== null) {
            $mailer = new mailer();
            $lien = getenv('APP_URL') . '/mes-commandes';
            $lienContact = getenv('APP_URL') . '/contact';

            if ($statut === 'terminée') {
                $message = "Bonjour" . $client['prenom'] . ",\n\n"
                    . "Votre commande " . $client['numero_commande'] . " est terminée. Merci de nous avoir fait confiance !\n"
                    . "Vous pouvez dès à présent vous connecter pour donner votre avis sur votre commande :\n"
                    . $lien . "\n\n"
                    . "À bientôt,\nJulie et José";
                $mailer->envoyer($client['email'], "Votre commande " . $client['numero_commande'] . " est terminée", $message);
            } elseif ($statut === 'en attente du retour de matériel') {
                $message = "Bonjour " . $client['prenom'] . ",\n\n"
                    . "Nous attendons le retour du matériel prêté pour la commande" . $client["numero_commande"] . ".\n"
                    . "Sans restitution sous 10 jours ouvrés, un montant de 600 € vous sera facturé, conformément à nos conditions générales de vente.\n"
                    . "Pour rendre le matériel, merci de prendre contact avec nous :\n"
                    . $lienContact . "\n\n"
                    . "À bientôt,\nJulie et José";
                $mailer->envoyer($client['email'], "Retour du matériel prêté – commande " . $client['numero_commande'], $message);
            }
        }

        header('Location: /employe?statut_modifie=1');
    }

    public function annulerCommande(): void
    {
        $this->exigerRole([1, 2]);

        // l'id arrive dans l'adresse (?id=…), puis dans le champ caché (POST)
        $idCommande = (int) ($_POST['id_commande'] ?? $_GET['id'] ?? 0);
        $erreurs = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $modeContact = trim($_POST['mode_contact'] ?? '');
            $motif = trim($_POST['motif'] ?? '');

            if (!in_array($modeContact, self::MODES_CONTACT)) {                                // le mode n'est pas dans la liste blanche
                $erreurs[] = "Indiquez comment le client a été contacté (GSM ou mail).";
            }
            if (empty($motif)) {                                // le motif est vide
                $erreurs[] = "Le motif d'annulation est obligatoire.";
            }

            if (empty($erreurs)) {
                $commandeManager = new CommandeManager(getPdo());
                $annulee = $commandeManager->annulerParEmploye($idCommande, $modeContact, $motif);
                if ($annulee) {
                    $client = $commandeManager->trouverClient($idCommande);

                    if ($client !== null) {
                        if ($modeContact === 'GSM') {
                            $intro = "Suite à notre appel téléphonique";
                        } else {
                            $intro = "Suite à l'envoi de notre mail";
                        }
                        $message = "Bonjour " . $client['prenom'] . ",\n\n"
                            . $intro . ", nous vous confirmons l'annulation de votre commande " . $client['numero_commande'] . ".\n"
                            . "Pour rappel, le motif est le suivant : " . $motif . "\n\n"
                            . "À bientôt,\nJulie et José";
                        $mailer = new Mailer();
                        $mailer->envoyer($client['email'], "Annulation de votre commande " . $client['numero_commande'], $message);
                    }

                    header('Location: /employe?annulee=1');
                    exit;
                }
                $erreurs[] = "Cette commande ne peut pas être annulée (déjà annulée ou terminée).";
            }
        }

        $this->afficher('employe/annuler', [
            'titre' => 'Annuler une commande',
            'idCommande' => $idCommande,
            'erreurs' => $erreurs,
        ]);
    }

    public function avisEnAttente(): void
    {
        $this->exigerRole([1, 2]);      // admin et employé seulement

        $avisManager = new AvisManager(getPdo());
        $avis = $avisManager->trouverEnAttente();

        $traite = isset($_GET['traite']);
        $erreur = isset($_GET['erreur']);

        $this->afficher('employe/avis', [
            'titre' => 'Avis des clients',
            'avis' => $avis,
            'traite' => $traite,
            'erreur' => $erreur,
        ]);
    }

    public function traiterAvis(): void
    {
        $this->exigerRole([1, 2]);

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /employe/avis');
            exit;
        }

        $idAvis = (int) ($_POST['id_avis'] ?? 0);
        $statut = trim($_POST['statut'] ?? '');

        if (!in_array($statut, self::STATUTS_AVIS)) {
            header('Location: /employe/avis?erreur=1');
            exit;
        }

        $avisManager = new AvisManager(getPdo());

        if ($avisManager->changerStatut($idAvis, $statut)) {
            header('Location: /employe/avis?traite=1');
        } else {
            header('Location: /employe/avis?erreur=1');
        }
        exit;
    }

    public function menus(): void
    {
        $this->exigerRole([1, 2]);

        $menuManager = new MenuManager(getPdo());
        $menus = $menuManager->findAll();

        $this->afficher('employe/menus', [
            'titre' => 'Gérer les menus',
            'menus' => $menus,
            'cree' => isset($_GET['cree']),
            'modifie' => isset($_GET['modifie']),
            'supprime' => isset($_GET['supprime']),
            'dejaCommande' => isset($_GET['deja_commande']),
            'erreur' => isset($_GET['erreur']),

        ]);
    }

    public function creerMenu(): void
    {
        $this->exigerRole([1, 2]);

        $menuManager = new MenuManager(getPdo());
        $themes = $menuManager->trouverThemesPourFormulaire();
        $regimes = $menuManager->trouverRegimesPourFormulaire();
        $erreur = '';



        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // 1. Récupérer les champs
            $titre = trim($_POST['titre'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $conditions = trim($_POST['conditions'] ?? '');
            $stock = (int) ($_POST['stock'] ?? 0);
            $prix = (float) ($_POST['prix_par_personne'] ?? 0);
            $nbreMini = (int) ($_POST['nbre_personne_mini'] ?? 0);
            $idTheme = (int) ($_POST['id_theme'] ?? 0);
            $idRegime = (int) ($_POST['id_regime'] ?? 0);

            if (empty($titre)) {
                $erreur = "Le titre est obligatoire";
            }
            if (empty($description)) {
                $erreur = "La description est obligatoire";
            }
            if (empty($conditions)) {
                $erreur = "Ajouter une condition";
            }
            if ($stock < 1) {
                $erreur = "Le stock doit être au moins de 1";
            }
            if ($nbreMini < 1) {
                $erreur = "1 personne minimum";
            }
            if ($prix <= 0) {
                $erreur = "Afficher un prix supérieur à 0";
            }
            if (!in_array($idTheme, array_column($themes, 'id_theme'))) {
                $erreur = "Le thème choisi n'existe pas";
            }
            if (!in_array($idRegime, array_column($regimes, 'id_regime'))) {
                $erreur = "Le régime choisi n'existe pas";
            }
            if (empty($erreur)) {
                $donnees = [
                    'titre' => $titre,
                    'description' => $description,
                    'conditions' => $conditions,
                    'stock' => $stock,
                    'prix_par_personne' => $prix,
                    'nbre_personne_mini' => $nbreMini,
                    'id_theme' => $idTheme,
                    'id_regime' => $idRegime,
                ];
                $menuManager->creer($donnees);
                header('Location: /employe/menus?cree=1');
                exit;
            }
        }

        $this->afficher('employe/creer-menu', [
            'titre' => 'Créer un menu',
            'themes' => $themes,
            'regimes' => $regimes,
            'erreur' => $erreur,
        ]);
    }

    public function modifierMenu(): void
    {
        $this->exigerRole([1, 2]);

        $menuManager = new MenuManager(getPdo());
        $themes = $menuManager->trouverThemesPourFormulaire();
        $regimes = $menuManager->trouverRegimesPourFormulaire();
        $erreur = '';

        $idMenu = (int) ($_GET['id'] ?? 0);
        $menu = $menuManager->findById($idMenu);

        if ($menu === null) {
            header('Location: /employe/menus?erreur=1');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // 1. Récupérer les champs
            $titre = trim($_POST['titre'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $conditions = trim($_POST['conditions'] ?? '');
            $stock = (int) ($_POST['stock'] ?? 0);
            $prix = (float) ($_POST['prix_par_personne'] ?? 0);
            $nbreMini = (int) ($_POST['nbre_personne_mini'] ?? 0);
            $idTheme = (int) ($_POST['id_theme'] ?? 0);
            $idRegime = (int) ($_POST['id_regime'] ?? 0);

            if (empty($titre)) {
                $erreur = "Le titre est obligatoire";
            }
            if (empty($description)) {
                $erreur = "La description est obligatoire";
            }
            if (empty($conditions)) {
                $erreur = "Ajouter une condition";
            }
            if ($stock < 1) {
                $erreur = "Le stock doit être au moins de 1";
            }
            if ($nbreMini < 1) {
                $erreur = "1 personne minimum";
            }
            if ($prix <= 0) {
                $erreur = "Afficher un prix supérieur à 0";
            }
            if (!in_array($idTheme, array_column($themes, 'id_theme'))) {
                $erreur = "Le thème choisi n'existe pas";
            }
            if (!in_array($idRegime, array_column($regimes, 'id_regime'))) {
                $erreur = "Le régime choisi n'existe pas";
            }
            if (empty($erreur)) {
                $donnees = [
                    'titre' => $titre,
                    'description' => $description,
                    'conditions' => $conditions,
                    'stock' => $stock,
                    'prix_par_personne' => $prix,
                    'nbre_personne_mini' => $nbreMini,
                    'id_theme' => $idTheme,
                    'id_regime' => $idRegime,
                ];
                $menuManager->modifier($idMenu, $donnees);
                header('Location: /employe/menus?modifie=1');
                exit;
            }
        }

        $this->afficher('employe/modifier-menu', [
            'titre' => 'Modifier un menu',
            'menu' => $menu,
            'themes' => $themes,
            'regimes' => $regimes,
            'erreur' => $erreur,
        ]);
    }

    public function supprimerMenu(): void
    {
        $this->exigerRole([1, 2]);

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /employe/menus');
            exit;
        }

        $idMenu = (int) ($_POST['id_menu'] ?? 0);

        $menuManager = new MenuManager(getPdo());

        if ($menuManager->dejaCommande($idMenu)) {
            header('Location: /employe/menus?deja_commande=1');
            exit;
        }

        if ($menuManager->supprimer($idMenu)) {
            header('Location: /employe/menus?supprime=1');
        } else {
            header('Location: /employe/menus?erreur=1');
        }
        exit;
    }

    public function plats(): void
    {
        $this->exigerRole([1, 2]);

        $platManager = new PlatManager(getPdo());
        $plats = $platManager->trouverTous();

        $this->afficher('employe/plats', [
            'titre' => 'Gérer les plats',
            'plats' => $plats,
            'cree' => isset($_GET['cree']),
        ]);
    }

    public function creerPlat(): void
    {
        $this->exigerRole([1, 2]);

        $platManager = new PlatManager(getPdo());
        $erreur = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = trim($_POST['nom'] ?? '');
            $type = trim($_POST['type'] ?? '');

            if (empty($nom)) {
                $erreur = "Le nom est obligatoire";
            }
            if (!in_array($type, self::TYPES_PLAT)) {
                $erreur = "Le type choisi n'existe pas";
            }
            if (empty($erreur)) {
                $platManager->creer($nom, $type);
                header('Location: /employe/plats?cree=1');
                exit;
            }
        }

        $this->afficher('employe/creer-plat', [
            'titre'=> 'Créer un plat',
            'erreur' => $erreur,
            'types' => self::TYPES_PLAT,
        ]);
    }
}
