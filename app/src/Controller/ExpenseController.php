<?php

declare(strict_types=1);

namespace App\Controller;

use App\Core\Controller\AbstractCoreController;
use App\Services\ExpenseServiceInterface;
use App\Services\TokenServiceInterface;
use App\Transfer\ExpenseFilterTransfer;
use App\Transfer\ExpenseTransfer;
use App\Transfer\PaginateTransfer;
use App\Transfer\UserTransfer;
use App\Validator\ValidatorFactoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Security\Core\Exception\AuthenticationCredentialsNotFoundException;

class ExpenseController extends AbstractCoreController
{
    /**
     * @param \App\Validator\ValidatorFactory $validatorFactory
     * @param \App\Services\ExpenseService $expenseService
     */
    public function __construct(
        private readonly TokenServiceInterface $tokenService,
        private readonly ValidatorFactoryInterface $validatorFactory,
        private readonly ExpenseServiceInterface $expenseService,
    ) {
        parent::__construct($this->tokenService);
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
        $expenseTransfer = new ExpenseTransfer();
        $expenseTransfer->setCategoryId($requestBody["categoryId"]);
        $expenseTransfer->setUserId($this->getUserIdFromSession($request));
        $expenses = $this->expenseService->create($expenseTransfer);

        return new JsonResponse(['expenses' => $expenses]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function filter(Request $request): JsonResponse
    {
        if (!$this->isUserSessionValid($request)) {
            throw new AuthenticationCredentialsNotFoundException();
        }

        $requestBody = $request->toArray();
        $validationResponse = $this->validatorFactory->createExpenseFilterValidator()->validate($requestBody);
        if ($validationResponse->hasErrors()) {
            throw new BadRequestHttpException(message: $validationResponse->errors[0]);
        }
        $expenseTransfer = new ExpenseFilterTransfer();
        $expenseTransfer->setAmountGrater($requestBody["amount"]);
        $expenseTransfer->setCategoryId((int) $requestBody["categoryId"]);
        $expenseTransfer->setUserId($this->getUserIdFromSession($request));
        $paginateTransfer = $this->getPaginateTransfer($request);
        $expenses = $this->expenseService->filter($expenseTransfer, $paginateTransfer);

        return new JsonResponse(['expenses' => $expenses]);
    }


    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function aggregate(Request $request): JsonResponse
    {
        if (!$this->isUserSessionValid($request)) {
            throw new AuthenticationCredentialsNotFoundException();
        }

        $requestBody = $request->toArray();
        $validationResponse = $this->validatorFactory->createExpenseFilterValidator()->validate($requestBody);
        if ($validationResponse->hasErrors()) {
            throw new BadRequestHttpException(message: $validationResponse->errors[0]);
        }

        $expenseTransfer = new ExpenseFilterTransfer();
        $expenseTransfer->setAmountGrater($requestBody["amount"]);
        $expenseTransfer->setCategoryId((int) $requestBody["categoryId"]);
        $expenseTransfer->setUserId($this->getUserIdFromSession($request));
        $paginateTransfer = $this->getPaginateTransfer($request);
        $expenses = $this->expenseService->filter($expenseTransfer, $paginateTransfer);

        return new JsonResponse(['expenses' => $expenses]);
    }
}
