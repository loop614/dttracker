<?php

declare(strict_types=1);

namespace App\Validator;

use App\Transfer\ValidationResponseTransfer;

final class ExpenseValidator implements ValidatorInterface
{
    private const REQUIRED_FIELDS = ["description", "amount", "categoryId"];

    /**
     * @param array $input
     *
     * @return \App\Transfer\ValidationResponseTransfer
     */
    public function validate(array $input): ValidationResponseTransfer
    {
        $validationResponse = new ValidationResponseTransfer();
        foreach (self::REQUIRED_FIELDS as $requiredField) {
            if (!isset($input[$requiredField])) {
                $validationResponse->addError($requiredField . " needs to be set");
                return $validationResponse;
            }
        }

        return $validationResponse;
    }
}
