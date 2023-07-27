<?php

declare(strict_types=1);

namespace App\Validator;

use App\Services\UserServiceInterface;

interface ValidatorFactoryInterface
{
    /**
     * @return \App\Validator\ValidatorInterface
     */
    public function createRegisterValidator(UserServiceInterface $service): ValidatorInterface;

    /**
     * @return \App\Validator\ValidatorInterface
     */
    public function createLoginValidator(): ValidatorInterface;

    /**
     * @return \App\Validator\ValidatorInterface
     */
    public function createCategoryValidator(): ValidatorInterface;

    /**
     * @return \App\Validator\ValidatorInterface
     */
    public function createExpenseValidator(): ValidatorInterface;

    /**
     * @return \App\Validator\ValidatorInterface
     */
    public function createIncomeValidator(): ValidatorInterface;
}
