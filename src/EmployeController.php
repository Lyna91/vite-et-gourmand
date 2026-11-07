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

    private const MODES_CONTACT = ['GSM', 'mail'];

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
                $mailer->envoyer($client['email'], "votre commande" . $client['numero_commande'] . "est terminée", $message);
            } elseif ($statut === 'en attente du retour de matériel') {
                $message = "Bonjour " . $client['prenom'] . ",\n\n"
                    . "Nous attendons le retour du matériel prêter pour la commande" . $client["numero_commande"] . ".\n"
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
}
