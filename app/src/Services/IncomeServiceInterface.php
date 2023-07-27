<?php

declare(strict_types=1);

namespace App\Services;

use App\Entity\Income;
use App\Transfer\IncomeFilterTransfer;
use App\Transfer\IncomeTransfer;
use App\Transfer\PaginateTransfer;
use App\Transfer\UserTransfer;

interface IncomeServiceInterface
{
    /**
     * @param \App\Transfer\IncomeTransfer $expenseTransfer
     *
     * @return \App\Entity\income
     */
    public function create(IncomeTransfer $expenseTransfer): Income;

    /**
     * @param \App\Transfer\UserTransfer $userTransfer
     * @param \App\Transfer\PaginateTransfer $paginateTransfer
     *
     * @return array
     */
    public function getIncomesByUser(UserTransfer $userTransfer, PaginateTransfer $paginateTransfer): array;

    /**
     * @param \App\Transfer\IncomeFilterTransfer $incomeFilterTransfer
     * @param \App\Transfer\PaginateTransfer $paginateTransfer
     *
     * @return array
     */
    public function filter(IncomeFilterTransfer $incomeFilterTransfer, PaginateTransfer $paginateTransfer): array;

    /**
     * @param \App\Transfer\IncomeFilterTransfer $incomeFilterTransfer
     *
     * @return array
     */
    public function aggregate(IncomeFilterTransfer $incomeFilterTransfer): array;
}
