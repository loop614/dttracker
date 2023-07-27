<?php

declare(strict_types=1);

namespace App\Services;

use App\Entity\Expense;
use App\Transfer\ExpenseFilterTransfer;
use App\Transfer\ExpenseTransfer;
use App\Transfer\PaginateTransfer;
use App\Transfer\UserTransfer;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;

final class ExpenseService implements ExpenseServiceInterface
{
    /**
     * @param \Doctrine\ORM\EntityManagerInterface $entityManager
     * @param \App\Services\UserServiceInterface $userService
     * @param \App\Services\CategoryServiceInterface $categoryService
     */
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly UserServiceInterface $userService,
        private readonly CategoryServiceInterface $categoryService,
    ) {
    }

    /**
     * @param \App\Transfer\ExpenseTransfer $expenseTransfer
     *
     * @return \App\Entity\Expense
     */
    public function create(ExpenseTransfer $expenseTransfer): Expense
    {
        $expense = new Expense();
        $user = $this->userService->getUserById($expenseTransfer->getUserId());
        $category = $this->categoryService->getCategoryById($expenseTransfer->getCategoryId());
        $expense->setUser($user);
        $expense->setCategory($category);

        $expense->setAmount($expenseTransfer->getAmount());
        $expense->setDescription($expenseTransfer->getDescription());

        $this->entityManager->persist($expense);
        $this->entityManager->flush();

        $this->userService->updateBalance($user, -1 * $expense->getAmount());

        return $expense;
    }

    /**
     * @param \App\Transfer\UserTransfer $userTransfer
     * @param \App\Transfer\PaginateTransfer $paginateTransfer
     *
     * @return array
     */
    public function getExpensesByUser(UserTransfer $userTransfer, PaginateTransfer $paginateTransfer): array
    {
        return $this->entityManager
            ->getRepository(Expense::class)
            ->createQueryBuilder('eqb')
            ->select('eqb', 'c', 'u')
            ->leftJoin('eqb.category', 'c')
            ->leftJoin('eqb.user', 'u')
            ->where('u.id = :user_id')
            ->setParameters(['user_id' => $userTransfer->getId()])
            ->setFirstResult($paginateTransfer->getStart())
            ->setMaxResults($paginateTransfer->getSize())
            ->getQuery()
            ->getArrayResult();
    }

    /**
     * @param \App\Transfer\ExpenseFilterTransfer $expenseFilterTransfer
     * @param \App\Transfer\PaginateTransfer $paginateTransfer
     *
     * @return array
     */
    public function filter(
        ExpenseFilterTransfer $expenseFilterTransfer,
        PaginateTransfer $paginateTransfer
    ): array {
        $query =  $this->entityManager
            ->getRepository(Expense::class)
            ->createQueryBuilder('eqb')
            ->select('eqb', 'c', 'u')
            ->leftJoin('eqb.category', 'c')
            ->leftJoin('eqb.user', 'u')
            ->where('u.id = :user_id');

        $this->filterOptional($expenseFilterTransfer, $query);

        $query->setParameter('user_id', $expenseFilterTransfer->getUserId())
            ->setFirstResult($paginateTransfer->getStart())
            ->setMaxResults($paginateTransfer->getSize());

        return $query->getQuery()->getArrayResult();
    }

    /**
     * @param \App\Transfer\ExpenseFilterTransfer $expenseFilterTransfer
     * @param \Doctrine\ORM\QueryBuilder $query
     *
     * @return void
     */
    private function filterOptional(ExpenseFilterTransfer $expenseFilterTransfer, QueryBuilder $query): void
    {
        if ($expenseFilterTransfer->getCategoryId() !== null) {
            $query
                ->andWhere('c.id = :categoryId')
                ->setParameter("categoryId", $expenseFilterTransfer->getCategoryId());
        }

        if ($expenseFilterTransfer->getAmountGrater() !== null) {
            $query
                ->andWhere('eqb.amount > :amountGrater')
                ->setParameter("amountGrater", $expenseFilterTransfer->getAmountGrater());
        }

        if ($expenseFilterTransfer->getAmountLess() !== null) {
            $query
                ->andWhere('eqb.amount < :amountLess')
                ->setParameter("amountLess", $expenseFilterTransfer->getAmountLess());
        }

        if ($expenseFilterTransfer->getStartDate() !== null) {
            $query
                ->andWhere('eqb.created_at > :start_date')
                ->setParameter("start_date", $expenseFilterTransfer->getStartDate());
        }

        if ($expenseFilterTransfer->getEndDate() !== null) {
            $query
                ->andWhere('eqb.created_at < :end_date')
                ->setParameter("end_date", $expenseFilterTransfer->getEndDate());
        }
    }
}
