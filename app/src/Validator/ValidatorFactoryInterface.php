<?php

declare(strict_types=1);

namespace App\Validator;

use App\Core\Validator\ValidatorInterface;
use App\Services\UserServiceInterface;

interface ValidatorFactoryInterface
{
    /**
     * @param \App\Services\UserServiceInterface $service
     *
     * @return \App\Core\Validator\ValidatorInterface
     */
    public function createRegisterValidator(UserServiceInterface $service): ValidatorInterface;

    /**
     * @return \App\Core\Validator\ValidatorInterface
     */
    public function createLoginValidator(): ValidatorInterface;

    /**
     * @return \App\Core\Validator\ValidatorInterface
     */
    public function createCategoryValidator(): ValidatorInterface;

    /**
     * @return \App\Core\Validator\ValidatorInterface
     */
    public function createExpenseValidator(): ValidatorInterface;

    /**
     * @return \App\Core\Validator\ValidatorInterface
     */
    public function createIncomeValidator(): ValidatorInterface;

    /**
     * @return \App\Core\Validator\ValidatorInterface
     */
    public function createExpenseFilterValidator(): ValidatorInterface;

    /**
     * @return \App\Core\Validator\ValidatorInterface
     */
    public function createIncomeFilterValidator(): ValidatorInterface;
}
