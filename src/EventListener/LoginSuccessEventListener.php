<?php

namespace App\EventListener;

use App\Entity\Personne;
use App\Entity\Proprietaire;
use App\Entity\Serveur;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

#[AsEventListener(event: LoginSuccessEvent::class)]
final class LoginSuccessEventListener
{
    use TargetPathTrait;

    public function __construct(private UrlGeneratorInterface $urlGenerator)
    {
    }

    public function __invoke(LoginSuccessEvent $event): void
    {
        $request = $event->getRequest();
        $session = $request->getSession();
        $firewallName = $event->getFirewallName();

        $targetPath = $this->getTargetPath($session, $firewallName);

        if ($targetPath) {
            $session->remove('_security.'.$firewallName.'.target_path');

            $event->setResponse(new RedirectResponse($targetPath));

            return;
        }

        $user = $event->getUser();

        if (!$user instanceof Personne) {
            return;
        }

        if ($user instanceof Proprietaire) {
            $url = $this->urlGenerator->generate('app_proprietaire');
            $event->setResponse(new RedirectResponse($url));

            return;
        }

        if ($user instanceof Serveur) {
            $restaurant = $user->getRestaurant();

            if ($restaurant) {
                $url = $this->urlGenerator->generate('app_commande_index', [
                    'id' => $restaurant->getId(),
                ]);
                $event->setResponse(new RedirectResponse($url));

                return;
            }
        }

        $event->setResponse(new RedirectResponse($this->urlGenerator->generate('app_home')));
    }
}
