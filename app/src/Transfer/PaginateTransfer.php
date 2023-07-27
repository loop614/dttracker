<?php

declare(strict_types=1);

namespace App\Transfer;

final class PaginateTransfer implements TransferInterface
{
    public function __construct(
        public readonly int $start,
        public readonly int $size
    ) {
    }
}
