<?php

declare(strict_types=1);

namespace App\Services;

use App\Entity\Expense;
use App\Transfer\ExpenseTransfer;
use App\Transfer\PaginateTransfer;
use App\Transfer\UserTransfer;

interface ExpenseServiceInterface
{
    /**
     * @param \App\Transfer\ExpenseTransfer $expenseTransfer
     *
     * @return \App\Entity\Expense
     */
    public function create(ExpenseTransfer $expenseTransfer): Expense;

    /**
     * @param \App\Transfer\UserTransfer $userTransfer
     * @param \App\Transfer\PaginateTransfer $paginateTransfer
     *
     * @return array
     */
    public function getExpensesByUser(UserTransfer $userTransfer, PaginateTransfer $paginateTransfer): array;
}
