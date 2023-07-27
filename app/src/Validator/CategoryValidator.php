<?php

declare(strict_types=1);

namespace App\Validator;

use App\Transfer\ValidationResponseTransfer;

final class CategoryValidator implements ValidatorInterface
{
    private const MAX_LENGTH_CATEGORY_NAME = 256;

    /**
     * @param array $input
     *
     * @return \App\Transfer\ValidationResponseTransfer
     */
    public function validate(array $input): ValidationResponseTransfer
    {
        $validationResponse = new ValidationResponseTransfer();
        if (!isset($input["name"])) {
            $validationResponse->addError('Category must have name');
            return $validationResponse;
        }
        if (strlen($input["name"]) > self::MAX_LENGTH_CATEGORY_NAME) {
            $validationResponse->addError('Category name too long');
            return $validationResponse;
        }

        return $validationResponse;
    }
}
