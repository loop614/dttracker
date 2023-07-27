<?php

declare(strict_types=1);

namespace App\Controller;

use App\Core\Controller\AbstractCoreController;
use App\Services\CategoryServiceInterface;
use App\Transfer\CategoryTransfer;
use App\Transfer\UserTransfer;
use App\Validator\ValidatorFactoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Security\Core\Exception\AuthenticationCredentialsNotFoundException;

class CategoryController extends AbstractCoreController
{
    /**
     * @param \App\Validator\ValidatorFactoryInterface $validatorFactory
     * @param \App\Services\CategoryServiceInterface $categoryService
     */
    public function __construct(
        private readonly ValidatorFactoryInterface $validatorFactory,
        private readonly CategoryServiceInterface $categoryService
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

        $userTransfer = new UserTransfer();
        $userTransfer->setId($this->getUserIdFromSession($request));
        $paginateTransfer = $this->getPaginateTransfer($request);
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
            throw new BadRequestHttpException(message: $validationResponse->getErrors()[0]);
        }
        $categoryTransfer = new CategoryTransfer();
        $categoryTransfer->setName($requestArray["name"]);
        $categoryTransfer->setUserId($this->getUserIdFromSession($request));
        $this->categoryService->create($categoryTransfer);

        return new JsonResponse(['data' => true]);
    }
}
