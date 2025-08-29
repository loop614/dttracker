<?php

declare(strict_types= 1);

namespace App\EventSubscriber;

use DateInterval;
use DateTimeImmutable;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AuthenticationCredentialsNotFoundException;


class UserSessionEventSubscriber implements EventSubscriberInterface
{
    private const SESSION_DURATION_DAYS = 1;

    private const REQUIRED_SESSION_FIELDS = ["userId", "userEmail", "sessionStart"];

    public static function getSubscribedEvents(): array
    {
        // return the subscribed events, their methods and priorities
        return [
            KernelEvents::REQUEST => [
                ['processException', 10],
                ['logException', 0],
                ['notifyException', -10],
            ],
        ];
    }

    public function processException(RequestEvent $event): void
    {
        $request = $event->getRequest();
        if (in_array($request->getPathInfo(), ['/login', '/logout', '/register'])) {
            return;
        }

        if (!$this->isUserSessionValid($request)) {
            throw new AuthenticationCredentialsNotFoundException();
        }
    }

    public function logException(RequestEvent $event): void
    {
        // ...
    }

    public function notifyException(RequestEvent $event): void
    {
        // ...
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return bool
     */
    protected function isUserSessionValid(Request $request): bool
    {
        if ($request->getSession()->get("user") === null) {
            return false;
        }
        $userSessionData = $request->getSession()->get("user");

        foreach (self::REQUIRED_SESSION_FIELDS as $requiredSessionField) {
            if (!isset($userSessionData[$requiredSessionField])) {
                return false;
            }
        }

        $now = new DatetimeImmutable();
        $interval = new DateInterval("P" . self::SESSION_DURATION_DAYS . "D");

        if ($userSessionData['sessionStart'] < $now->sub($interval)) {
            $this->deleteUserFromSession($request);
            return false;
        }

        return true;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return void
     */
    protected function deleteUserFromSession(Request $request): void
    {
        $request->getSession()->set("user", null);
    }
}
