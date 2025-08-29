<?php

declare(strict_types=1);

namespace App\Controller;

use App\Core\Controller\AbstractCoreController;
use App\Services\ExpenseServiceInterface;
use App\Transfer\ExpenseFilterTransfer;
use App\Transfer\ExpenseTransfer;
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
        private readonly ValidatorFactoryInterface $validatorFactory,
        private readonly ExpenseServiceInterface $expenseService,
    ) {
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function list(Request $request): JsonResponse
    {
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
        $requestBody = $request->toArray();
        $validationResponse = $this->validatorFactory->createExpenseValidator()->validate($requestBody);
        if ($validationResponse->hasErrors()) {
            throw new BadRequestHttpException(message: $validationResponse->getErrors()[0]);
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
        $requestBody = $request->toArray();
        $validationResponse = $this->validatorFactory->createExpenseFilterValidator()->validate($requestBody);
        if ($validationResponse->hasErrors()) {
            throw new BadRequestHttpException(message: $validationResponse->getErrors()[0]);
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
        $requestBody = $request->toArray();
        $validationResponse = $this->validatorFactory->createExpenseFilterValidator()->validate($requestBody);
        if ($validationResponse->hasErrors()) {
            throw new BadRequestHttpException(message: $validationResponse->getErrors()[0]);
        }

        $expenseTransfer = $this->mapExpenseFilterTransfer($requestBody);
        $expenseTransfer->setUserId($this->getUserIdFromSession($request));
        $expenseAggregate = $this->expenseService->aggregate($expenseTransfer);

        return new JsonResponse(['expense_aggregate' => $expenseAggregate]);
    }

    /**
     * @param array $requestBody
     *
     * @return \App\Transfer\ExpenseFilterTransfer
     */
    private function mapExpenseFilterTransfer(array $requestBody): ExpenseFilterTransfer
    {
        $expenseTransfer = new ExpenseFilterTransfer();
        $expenseTransfer->setAmountGrater($requestBody["amount_greater"] ?? null);
        $expenseTransfer->setAmountLess($requestBody["amount_less"] ?? null);
        $expenseTransfer->setEndDate($requestBody["end_date"] ?? null);
        $expenseTransfer->setStartDate($requestBody["start_date"] ?? null);
        $expenseTransfer->setCategoryId($requestBody["category_id"] ?? null);

        return $expenseTransfer;
    }
}
