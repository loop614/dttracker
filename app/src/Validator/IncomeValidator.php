<?php

declare(strict_types=1);

namespace App\Validator;

use App\Core\Validator\ValidatorInterface;
use App\Transfer\ValidationResponseTransfer;

final class IncomeValidator implements ValidatorInterface
{
    /**
     * @param array $input
     *
     * @return \App\Transfer\ValidationResponseTransfer
     */
    public function validate(array $input): ValidationResponseTransfer
    {
        $validationResponse = new ValidationResponseTransfer();
        if (!isset($input["amount"])) {
            $validationResponse->addError("Amount needs to be set");
            return $validationResponse;
        }

        return $validationResponse;
    }
}
