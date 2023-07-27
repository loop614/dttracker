<?php

declare(strict_types=1);

namespace App\Transfer;

use App\Entity\User;

final class CategoryTransfer implements TransferInterface
{
    public User $user;
    public function __construct(
        public readonly string $name
    ) {
    }
}
