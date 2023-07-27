<?php

declare(strict_types=1);

namespace App\Services;

use App\Transfer\LoginResponseTransfer;
use App\Transfer\UserTransfer;
use App\Entity\User;

interface UserServiceInterface
{
    /**
     * @param \App\Transfer\UserTransfer $userTransfer
     *
     * @return \App\Entity\User
     */
    public function create(UserTransfer $userTransfer): User;

    /**
     * @param string $email
     *
     * @return \App\Entity\User|null
    */
    public function findUserByEmail(string $email): ?User;

    /**
     * @param \App\Transfer\UserTransfer $userTransfer
     *
     * @return \App\Transfer\LoginResponseTransfer
     */
    public function login(UserTransfer $userTransfer): LoginResponseTransfer;

    /**
     * @param int $userId
     *
     * @return \App\Entity\User|null
     */
    public function getUserById(int $userId): ?User;

    /**
     * @param \App\Entity\User $user
     * @param float $amount
     *
     * @return void
     */
    public function updateBalance(User $user, float $amount): void;
}
