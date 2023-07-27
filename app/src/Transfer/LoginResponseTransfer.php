<?php

declare(strict_types=1);

namespace App\Transfer;

use DateTimeImmutable;

final class LoginResponseTransfer
{
    /**
     * @var string[]
     */
    public array $messages = [];

    /**
     * @var string[]
     */
    public array $errors = [];

    /**
     * @var \App\Transfer\UserTransfer
     */
    public UserTransfer $user;

    /**
     * @var \DateTimeImmutable
     */
    public DateTimeImmutable $now;


    /**
     * @param string $message
     *
     * @return void
     */
    public function addMessage(string $message): void
    {
        $this->messages[] = $message;
    }

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
     * @return \App\Transfer\UserTransfer
     */
    public function getUser(): UserTransfer
    {
        return $this->user;
    }

    /**
     * @param \App\Transfer\UserTransfer $user
     *
     * @return void
     */
    public function setUser(UserTransfer $user): void
    {
        $this->user = $user;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getNow(): DateTimeImmutable
    {
        return $this->now;
    }

    /**
     * @param \DateTimeImmutable $now
     */
    public function setNow(DateTimeImmutable $now): void
    {
        $this->now = $now;
    }

    /**
     * @return bool
     */
    public function hasErrors(): bool
    {
        return count($this->errors) > 0;
    }
}
