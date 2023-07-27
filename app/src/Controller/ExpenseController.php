<?php

declare(strict_types=1);

namespace App\Controller;

use App\Services\CategoryServiceInterface;
use App\Services\ExpenseServiceInterface;
use App\Services\UserServiceInterface;
use App\Transfer\ExpenseTransfer;
use App\Transfer\PaginateTransfer;
use App\Transfer\UserTransfer;
use App\Validator\ValidatorFactoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Security\Core\Exception\AuthenticationCredentialsNotFoundException;

class ExpenseController extends AbstractTrackerController
{
    /**
     * @param \App\Validator\ValidatorFactory $validatorFactory
     * @param \App\Services\ExpenseService $expenseService
     * @param \App\Services\CategoryService $categoryService
     * @param \App\Services\UserService $userService
     */
    public function __construct(
        private readonly ValidatorFactoryInterface $validatorFactory,
        private readonly ExpenseServiceInterface $expenseService,
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
        $expenses = $this->expenseService->getExpensesByUser($userTransfer, $paginateTransfer);

        return new JsonResponse(['expenses' => $expenses]);
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

        $requestBody = $request->toArray();
        $validationResponse = $this->validatorFactory->createExpenseValidator()->validate($requestBody);
        if ($validationResponse->hasErrors()) {
            throw new BadRequestHttpException(message: $validationResponse->errors[0]);
        }
        $user = $this->userService->getUserById($this->getUserIdFromSession($request));
        $category = $this->categoryService->getCategoryById((int) $requestBody["categoryId"]);
        $expenseTransfer = new ExpenseTransfer($requestBody["amount"], $requestBody["description"]);
        $expenseTransfer->category = $category;
        $expenseTransfer->user = $user;
        $expenses = $this->expenseService->create($expenseTransfer);

        return new JsonResponse(['expenses' => $expenses]);
    }
}
