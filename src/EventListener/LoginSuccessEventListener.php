<?php

namespace App\EventListener;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;

#[AsEventListener(event: LoginSuccessEvent::class)]
final class LoginSuccessEventListener
{
    public function __construct(
        private UrlGeneratorInterface $urlGenerator,
    ) {
    }

    public function __invoke(LoginSuccessEvent $event): void
    {
        $user = $event->getUser();
        $roles = $user->getRoles();

        if (in_array('ROLE_PROPRIETAIRE', $roles)) {
            $response = new RedirectResponse($this->urlGenerator->generate('app_proprietaire'));
        } else {
            $response = new RedirectResponse($this->urlGenerator->generate('app_home'));
        }

        $event->setResponse($response);
    }
}
