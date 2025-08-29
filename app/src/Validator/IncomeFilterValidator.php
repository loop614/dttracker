<?php

declare(strict_types=1);

namespace App\Validator;

use App\Core\Validator\ValidatorInterface;
use App\Transfer\ValidationResponseTransfer;

final class IncomeFilterValidator implements ValidatorInterface
{
    private const ANY_OF_REQUIRED_FIELDS = [
        "start_date",
        "end_date",
        "amount_greater",
        "amount_less"
    ];

    /**
     * @param array $input
     *
     * @return \App\Transfer\ValidationResponseTransfer
     */
    public function validate(array $input): ValidationResponseTransfer
    {
        $validationResponse = new ValidationResponseTransfer();
        foreach (self::ANY_OF_REQUIRED_FIELDS as $requiredField) {
            if (isset($input[$requiredField])) {
                return $validationResponse;
            }
        }
        $validationResponse->addError('There should be atleast one filter paramether');

        return $validationResponse;
    }
}
