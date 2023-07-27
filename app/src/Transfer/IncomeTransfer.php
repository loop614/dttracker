<?php

declare(strict_types=1);

namespace App\Transfer;

use App\Entity\User;

final class incomeTransfer implements TransferInterface
{
    public int $userId;
    public User $user;

    public function __construct(
        public readonly float $amount,
        public readonly string $description
    ) {
    }
}
