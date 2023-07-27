<?php

declare(strict_types=1);

namespace App\Core\Validator;

use App\Transfer\ValidationResponseTransfer;

interface ValidatorInterface
{
    /**
     * @param array $input
     *
     * @return \App\Transfer\ValidationResponseTransfer
     */
    public function validate(array $input): ValidationResponseTransfer;
}
