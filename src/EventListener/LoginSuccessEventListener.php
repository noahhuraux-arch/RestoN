<?php

namespace App\EventListener;

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

    }
}
