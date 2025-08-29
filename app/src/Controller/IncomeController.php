<?php

declare(strict_types=1);

namespace App\Controller;

use App\Core\Controller\AbstractCoreController;
use App\Services\IncomeServiceInterface;
use App\Transfer\IncomeFilterTransfer;
use App\Transfer\incomeTransfer;
use App\Transfer\UserTransfer;
use App\Validator\ValidatorFactoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Security\Core\Exception\AuthenticationCredentialsNotFoundException;

class IncomeController extends AbstractCoreController
{
    /**
     * @param \App\Validator\ValidatorFactoryInterface $validatorFactory
     * @param \App\Services\IncomeServiceInterface $incomeService
     */
    public function __construct(
        private readonly ValidatorFactoryInterface $validatorFactory,
        private readonly IncomeServiceInterface $incomeService,
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
        $incomes = $this->incomeService->getIncomesByUser($userTransfer, $paginateTransfer);

        return new JsonResponse(['incomes' => $incomes]);
    }


    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function add(Request $request): JsonResponse
    {
        $requestArray = $request->toArray();
        $validationResponse = $this->validatorFactory->createIncomeValidator()->validate($requestArray);
        if ($validationResponse->hasErrors()) {
            throw new BadRequestHttpException(message: $validationResponse->getErrors()[0]);
        }
        $incomeTransfer = new IncomeTransfer();
        $incomeTransfer->setAmount($requestArray["amount"]);
        $incomeTransfer->setDescription($requestArray["description"]);
        $incomeTransfer->setUserId($this->getUserIdFromSession($request));
        $this->incomeService->create($incomeTransfer);

        return new JsonResponse(['data' => true]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function filter(Request $request): JsonResponse
    {
        $requestBody = $request->toArray();
        $validationResponse = $this->validatorFactory->createIncomeFilterValidator()->validate($requestBody);
        if ($validationResponse->hasErrors()) {
            throw new BadRequestHttpException($validationResponse->getErrors()[0]);
        }
        $incomeTransfer = $this->mapIncomeFilterTransfer($requestBody);
        $incomeTransfer->setUserId($this->getUserIdFromSession($request));
        $paginateTransfer = $this->getPaginateTransfer($request);
        $incomes = $this->incomeService->filter($incomeTransfer, $paginateTransfer);

        return new JsonResponse(['incomes' => $incomes]);
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

        $incomeTransfer = $this->mapIncomeFilterTransfer($requestBody);
        $incomeTransfer->setUserId($this->getUserIdFromSession($request));
        $incomeAggregate = $this->incomeService->aggregate($incomeTransfer);

        return new JsonResponse(['income_aggregate' => $incomeAggregate]);
    }

    /**
     * @param array $requestBody
     *
     * @return \App\Transfer\IncomeFilterTransfer
     */
    private function mapIncomeFilterTransfer(array $requestBody): IncomeFilterTransfer
    {
        $incomeTransfer = new IncomeFilterTransfer();
        $incomeTransfer->setAmountGrater($requestBody["amount_greater"] ?? null);
        $incomeTransfer->setAmountLess($requestBody["amount_less"] ?? null);
        $incomeTransfer->setEndDate($requestBody["end_date"] ?? null);
        $incomeTransfer->setStartDate($requestBody["start_date"] ?? null);

        return $incomeTransfer;
    }
}
