<?php

declare(strict_types=1);

namespace App\Transfer;

final class ValidationResponseTransfer
{
    private array $errors = [];

    /**
     * @param string $error
     *
     * @return void
     */
    public function addError(string $error): void
    {
        $this->errors[] = $error;
    }

    /**
     * @return string[]
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @return bool
     */
    public function hasErrors(): bool
    {
        return count($this->errors) > 0;
    }
}
