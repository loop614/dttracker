<?php

declare(strict_types=1);

namespace App\Transfer;

use App\Entity\User;
use DateTimeImmutable;

final class LoginResponseTransfer implements TransferInterface
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
     * @var \App\Entity\User
     */
    public User $user;

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
     * @return \App\Entity\User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param \App\Entity\User $user
     */
    public function setUser(User $user): void
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
