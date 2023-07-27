<?php

declare(strict_types=1);

namespace App\Controller;

use App\Services\UserServiceInterface;
use App\Validator\ValidatorFactoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Security\Core\Exception\AuthenticationCredentialsNotFoundException;

class IncomeController extends AbstractTrackerController
{
    /**
     * @param \App\Validator\ValidatorFactoryInterface $validatorFactory
     * @param \App\Services\UserServiceInterface $userService
     */
    public function __construct(
        private readonly ValidatorFactoryInterface $validatorFactory,
        private readonly IncomeServiceInterface $incomeService
    ) {
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function income(Request $request): JsonResponse
    {
        if (!$this->isUserSessionValid($request)) {
            throw new AuthenticationCredentialsNotFoundException();
        }

        $requestArray = $request->toArray();
        $validationResponse = $this->validatorFactory->createIncomeValidator()->validate($request->toArray());
        if ($validationResponse->hasErrors()) {
            throw new BadRequestHttpException(message: $validationResponse->errors[0]);
        }
        $user = $this->userService->getUserById($this->getUserIdFromSession($request));

        return new JsonResponse(['data' => true]);
    }
}
