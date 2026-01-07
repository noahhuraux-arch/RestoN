<?php

namespace App\Service;

use App\Entity\Restaurant;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class EmailService
{
    public function __construct(private MailerInterface $mailer, private UrlGeneratorInterface $urlGenerator, #[Autowire('%kernel.project_dir%')] private string $projectDir)
    {
    }

    public function sendWelcomeServeur(string $recipientEmail, string $prenom, string $password, Restaurant $restaurant): void
    {
        $loginUrl = $this->urlGenerator->generate('app_login', [], UrlGeneratorInterface::ABSOLUTE_URL);

        $logoPath = $this->projectDir.'/assets/images/logos/logo'.$restaurant->getId().'.png';
        $emailPrefix = strtolower(str_replace(' ', '-', $restaurant->getLibrestau()));

        $emailObj = (new TemplatedEmail())
            ->from(new Address($emailPrefix.'@reston.fr', $restaurant->getLibrestau()))
            ->to($recipientEmail)
            ->subject('Tes accès pour '.$restaurant->getLibrestau())
            ->htmlTemplate('emails/welcome.html.twig')
            ->context([
                'prenom' => $prenom,
                'userEmail' => $recipientEmail,
                'password' => $password,
                'restaurant' => $restaurant,
                'loginUrl' => $loginUrl,
            ]);

        if (file_exists($logoPath)) {
            $emailObj->embedFromPath($logoPath, 'logo-resto');
        }

        $this->mailer->send($emailObj);
    }
}
