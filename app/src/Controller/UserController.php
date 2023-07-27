<?php

declare(strict_types=1);

namespace App\Controller;

use App\Core\Controller\AbstractCoreController;
use App\Services\UserServiceInterface;
use App\Transfer\UserTransfer;
use App\Validator\ValidatorFactoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class UserController extends AbstractCoreController
{
    /**
     * @param \App\Validator\ValidatorFactory $validatorFactory
     * @param \App\Services\UserService $userService
     */
    public function __construct(
        private readonly ValidatorFactoryInterface $validatorFactory,
        private readonly UserServiceInterface $userService
    ) {
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function register(Request $request): JsonResponse
    {
        $requestBody = $request->toArray();
        $validationResponse = $this->validatorFactory
            ->createRegisterValidator($this->userService)
            ->validate($requestBody);
        if ($validationResponse->hasErrors()) {
            throw new BadRequestHttpException();
        }

        $userTransfer = new UserTransfer();
        $userTransfer->setEmail($requestBody["email"]);
        $userTransfer->setPassword($requestBody["password"]);
        $this->userService->create($userTransfer);

        return new JsonResponse(["result" => "success"]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function logIn(Request $request): JsonResponse
    {
        $requestBody = $request->toArray();
        $validationResponse = $this->validatorFactory
            ->createLoginValidator()
            ->validate($requestBody);
        if ($validationResponse->hasErrors()) {
            throw new BadRequestHttpException();
        }

        $userTransfer = new UserTransfer();
        $userTransfer->setEmail($requestBody["email"]);
        $userTransfer->setPassword($requestBody["password"]);
        $loginResponse = $this->userService->login($userTransfer);
        if ($loginResponse->hasErrors()) {
            throw new BadRequestHttpException();
        }
        $this->saveUserToSession($request, $loginResponse);

        return new JsonResponse(["result" => "success", "token" => $loginResponse->getUser()->getToken()]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function logOut(Request $request): JsonResponse
    {
        $this->deleteUserFromSession($request);

        return new JsonResponse(["result" => "success"]);
    }
}
