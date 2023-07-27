<?php

declare(strict_types=1);

namespace App\Validator;

use App\Core\Validator\ValidatorInterface;
use App\Services\UserServiceInterface;
use App\Transfer\ValidationResponseTransfer;

final class RegisterValidator implements ValidatorInterface
{
    public function __construct(
        public readonly UserServiceInterface $userService
    ) {
    }

    /**
     * @param array $input
     *
     * @return \App\Transfer\ValidationResponseTransfer
     */
    public function validate(array $input): ValidationResponseTransfer
    {
        $validationResponse = new ValidationResponseTransfer();
        $user = $this->userService->findUserByEmail($input["email"]);
        if ($user !== null) {
            $validationResponse->addError('User with the email address already exists');
        }

        return $validationResponse;
    }
}
