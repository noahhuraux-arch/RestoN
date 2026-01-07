<?php

namespace App\EventSubscriber;

use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class PasswordForceChangeSubscriber implements EventSubscriberInterface
{
    public function __construct(private Security $security, private UrlGeneratorInterface $urlGenerator)
    {
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $user = $this->security->getUser();
        $route = $event->getRequest()->attributes->get('_route');

        if ($user && method_exists($user, 'isMustChangePassword') && $user->isMustChangePassword()) {
            $allowedRoutes = ['app_force_change_password', 'app_logout', '_wdt', '_profiler'];

            if (!in_array($route, $allowedRoutes)) {
                $event->setResponse(new RedirectResponse($this->urlGenerator->generate('app_force_change_password')));
            }
        }
    }

    public static function getSubscribedEvents(): array
    {
        return ['kernel.request' => 'onKernelRequest'];
    }
}
