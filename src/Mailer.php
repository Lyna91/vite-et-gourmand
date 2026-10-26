<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// envoie les mails du site avec PHPMailer, selon les réglages du .env
class Mailer
{
    public function envoyer(string $destinataire, string $sujet, string $message, ?string $repondreA = null): bool
    {
        $mail = new PHPMailer(true);

        try {
            // le serveur d'envoi (Mailpit en local, un vrai serveur en production)
            $mail->isSMTP();
            $mail->Host = getenv('SMTP_HOST');
            $mail->Port = (int) getenv('SMTP_PORT');

            // identifiant et mot de passe, seulement s'il y en a (pas pour Mailpit)
            if (getenv('SMTP_USER')) {
                $mail->SMTPAuth = true;
                $mail->Username = getenv('SMTP_USER');
                $mail->Password = getenv('SMTP_PASSWORD');
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            }

            // le mail lui-même
            $mail->CharSet = 'UTF-8';
            $mail->setFrom(getenv('MAIL_FROM'), 'Vite & Gourmand');
            $mail->addAddress($destinataire);
            if ($repondreA !== null) {
                $mail->addReplyTo($repondreA);
            }
            $mail->Subject = $sujet;
            $mail->Body = $message;

            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log('Erreur d\'envoi de mail : ' . $mail->ErrorInfo);
            return false;
        }
    }
}
