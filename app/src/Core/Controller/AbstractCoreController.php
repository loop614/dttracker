<?php

declare(strict_types=1);

namespace App\Core\Controller;

use App\Services\TokenServiceInterface;
use App\Transfer\LoginResponseTransfer;
use App\Transfer\PaginateTransfer;
use DateInterval;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractCoreController extends AbstractController
{
    private const SESSION_DURATION_DAYS = 1;

    private const REQUIRED_SESSION_FIELDS = ["userId", "userEmail", "sessionStart"];

    public function __construct(private readonly TokenServiceInterface $tokenService) {}

    /**
     * @param string|null $token
     *
     * @return bool
     */
    protected function isUserAuthenticated(?string $token): bool
    {
        return $this->tokenService->verify($token);
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
     * @param \App\Transfer\LoginResponseTransfer $loginResponse
     *
     * @return void
     */
    protected function saveUserToSession(Request $request, LoginResponseTransfer $loginResponse): void
    {
        session_regenerate_id();
        $session = $request->getSession();
        $sessionData = [
            'userId' => $loginResponse->user->getId(),
            'userEmail' => $loginResponse->user->getEmail(),
            'sessionStart' => $loginResponse->now
        ];
        $session->set("user", $sessionData);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return int
     */
    protected function getUserIdFromSession(Request $request): int
    {
        return (int) $request->getSession()->get("user")["userId"];
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

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \App\Transfer\PaginateTransfer
     */
    protected function getPaginateTransfer(Request $request): PaginateTransfer
    {
        $paginateTransfer = new PaginateTransfer();
        $paginateTransfer->setStart((int) $request->get("start", 0));
        $paginateTransfer->setSize((int) $request->get("size", 10));

        return $paginateTransfer;
    }
}
