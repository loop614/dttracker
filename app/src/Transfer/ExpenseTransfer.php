<?php

declare(strict_types=1);

namespace App\Transfer;

use App\Entity\Category;
use App\Entity\User;

final class ExpenseTransfer implements TransferInterface
{
    public int $categoryId;
    public int $userId;
    public Category $category;
    public User $user;

    public function __construct(
        public readonly float $amount,
        public readonly string $description
    ) {
    }
}
