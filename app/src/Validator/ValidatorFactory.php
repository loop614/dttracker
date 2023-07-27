<?php

declare(strict_types=1);

namespace App\Validator;

use App\Core\Validator\ValidatorInterface;
use App\Services\UserServiceInterface;

final class ValidatorFactory implements ValidatorFactoryInterface
{
    /**
     * @param \App\Services\UserServiceInterface $service
     *
     * @return \App\Core\Validator\ValidatorInterface
     */
    public function createRegisterValidator(UserServiceInterface $service): ValidatorInterface
    {
        return new RegisterValidator($service);
    }

    /**
     * @return \App\Core\Validator\ValidatorInterface
     */
    public function createLoginValidator(): ValidatorInterface
    {
        return new LogInValidator();
    }

    /**
     * @return \App\Core\Validator\ValidatorInterface
     */
    public function createCategoryValidator(): ValidatorInterface
    {
        return new CategoryValidator();
    }

    /**
     * @return \App\Core\Validator\ValidatorInterface
     */
    public function createExpenseValidator(): ValidatorInterface
    {
        return new ExpenseValidator();
    }

    /**
     * @return \App\Core\Validator\ValidatorInterface
     */
    public function createIncomeValidator(): ValidatorInterface
    {
        return new IncomeValidator();
    }

    /**
     * @return \App\Core\Validator\ValidatorInterface
     */
    public function createExpenseFilterValidator(): ValidatorInterface
    {
        return new ExpenseFilterValidator();
    }

    /**
     * @return \App\Core\Validator\ValidatorInterface
     */
    public function createIncomeFilterValidator(): ValidatorInterface
    {
        return new IncomeFilterValidator();
    }
}
