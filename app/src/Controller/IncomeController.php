<?php

declare(strict_types=1);

namespace App\Controller;

use App\Core\Controller\AbstractCoreController;
use App\Services\IncomeServiceInterface;
use App\Transfer\ExpenseFilterTransfer;
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
        if ($this->isUserAuthenticated((string) $request->headers->get("authorization"))) {
            throw new AuthenticationCredentialsNotFoundException();
        }

        if (!$this->isUserSessionValid($request)) {
            throw new AuthenticationCredentialsNotFoundException();
        }

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
        if (!$this->isUserSessionValid($request)) {
            throw new AuthenticationCredentialsNotFoundException();
        }
        $requestArray = $request->toArray();
        $validationResponse = $this->validatorFactory->createIncomeValidator()->validate($requestArray);
        if ($validationResponse->hasErrors()) {
            throw new BadRequestHttpException(message: $validationResponse->errors[0]);
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
        if (!$this->isUserSessionValid($request)) {
            throw new AuthenticationCredentialsNotFoundException();
        }

        $requestBody = $request->toArray();
        $validationResponse = $this->validatorFactory->createExpenseFilterValidator()->validate($requestBody);
        if ($validationResponse->hasErrors()) {
            throw new BadRequestHttpException(message: $validationResponse->errors[0]);
        }
        $incomeTransfer = new IncomeFilterTransfer();
        $incomeTransfer->setAmountGrater($requestBody["amount"]);
        $incomeTransfer->setCategoryId((int) $requestBody["categoryId"]);
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
        if (!$this->isUserSessionValid($request)) {
            throw new AuthenticationCredentialsNotFoundException();
        }

        $requestBody = $request->toArray();
        $validationResponse = $this->validatorFactory->createExpenseFilterValidator()->validate($requestBody);
        if ($validationResponse->hasErrors()) {
            throw new BadRequestHttpException(message: $validationResponse->errors[0]);
        }

        $incomeTransfer = new IncomeFilterTransfer();
        $incomeTransfer->setAmountGrater($requestBody["amount"]);
        $incomeTransfer->setCategoryId((int) $requestBody["categoryId"]);
        $incomeTransfer->setUserId($this->getUserIdFromSession($request));
        $paginateTransfer = $this->getPaginateTransfer($request);
        $incomes = $this->incomeService->filter($incomeTransfer, $paginateTransfer);

        return new JsonResponse(['incomes' => $incomes]);
    }
}
