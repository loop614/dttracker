<?php

declare(strict_types=1);

namespace App\Validator;

use App\Services\ServiceInterface;
use App\Transfer\ValidationResponseTransfer;

final class LogInValidator implements ValidatorInterface
{
    /**
     * @param array $input
     *
     * @return \App\Transfer\ValidationResponseTransfer
     */
    public function validate(array $input): ValidationResponseTransfer
    {
        $validationResponse = new ValidationResponseTransfer();
        if (!isset($input["email"])) {
            $validationResponse->addError('Please provide email.');
        }

        if (!isset($input["password"])) {
            $validationResponse->addError('Please provide password.');
        }

        return $validationResponse;
    }
}
