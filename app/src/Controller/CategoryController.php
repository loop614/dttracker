<?php

declare(strict_types=1);

namespace App\Controller;

use App\Services\CategoryServiceInterface;
use App\Services\UserServiceInterface;
use App\Transfer\CategoryTransfer;
use App\Transfer\PaginateTransfer;
use App\Transfer\UserTransfer;
use App\Validator\ValidatorFactoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Security\Core\Exception\AuthenticationCredentialsNotFoundException;

class CategoryController extends AbstractTrackerController
{
    /**
     * @param \App\Validator\ValidatorFactoryInterface $validatorFactory
     * @param \App\Services\CategoryServiceInterface $categoryService
     * @param \App\Services\UserServiceInterface $userService
     */
    public function __construct(
        private readonly ValidatorFactoryInterface $validatorFactory,
        private readonly CategoryServiceInterface $categoryService,
        private readonly UserServiceInterface $userService
    ) {
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function list(Request $request): JsonResponse
    {
        if (!$this->isUserSessionValid($request)) {
            throw new AuthenticationCredentialsNotFoundException();
        }

        $paginatorStart = (int) $request->get("start", 0);
        $paginatorSize = (int) $request->get("size", 10);

        $user = $this->userService->getUserById($this->getUserIdFromSession($request));
        $userTransfer = new UserTransfer($user->getEmail(), $user->getPassword());
        $userTransfer->setId($user->getId());
        $paginateTransfer = new PaginateTransfer($paginatorStart, $paginatorSize);
        $categories = $this->categoryService->getCategoriesByUser($userTransfer, $paginateTransfer);

        return new JsonResponse(['categories' => $categories]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function add(Request $request): JsonResponse
    {
        if (!$this->isUserSessionValid($request)) {
            throw new AuthenticationCredentialsNotFoundException();
        }

        $requestArray = $request->toArray();
        $validationResponse = $this->validatorFactory->createCategoryValidator()->validate($request->toArray());
        if ($validationResponse->hasErrors()) {
            throw new BadRequestHttpException(message: $validationResponse->errors[0]);
        }
        $user = $this->userService->getUserById($this->getUserIdFromSession($request));
        $categoryTransfer = new CategoryTransfer($requestArray["name"]);
        $categoryTransfer->user = $user;
        $this->categoryService->create($categoryTransfer);

        return new JsonResponse(['data' => true]);
    }
}
