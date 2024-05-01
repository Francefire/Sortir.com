<?php

namespace App\Service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Transport\TransportInterface;
use Symfony\Component\Mime\Address;
use SymfonyCasts\Bundle\ResetPassword\Model\ResetPasswordToken;

class MailerService
{
    public function __construct(
        private readonly TransportInterface $mailer,
    )
    {
    }

    public function sendAccountCreated(string $to, string $username, string $password): void
    {
        $email = new TemplatedEmail();
        $email->from(new Address('noreply@sortir.com', 'NOREPLY - sortir.com'));
        $email->to($to);
        $email->subject('Compte crée');
        $email->htmlTemplate('emails/account_created.html.twig');
        $email->context([
            'username' => $username,
            'password' => $password,
        ]);

        $this->mailer->send($email);
    }

    public function sendResetPassword(string $to, ResetPasswordToken $resetToken): void
    {
        $email = (new TemplatedEmail())
            ->from(new Address('noreply@sortir.com', 'NOREPLY - Sortir.com'))
            ->to($to)
            ->subject('Réinitialisation de mot de passe')
            ->htmlTemplate('reset_password/email.html.twig')
            ->context([
                'resetToken' => $resetToken,
            ]);

        $this->mailer->send($email);
    }
}