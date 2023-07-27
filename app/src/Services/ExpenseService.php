<?php

declare(strict_types=1);

namespace App\Services;

use App\Entity\Expense;
use App\Transfer\ExpenseTransfer;
use App\Transfer\PaginateTransfer;
use App\Transfer\UserTransfer;
use Doctrine\ORM\EntityManagerInterface;

final class ExpenseService implements ExpenseServiceInterface
{
    /**
     * @param \Doctrine\ORM\EntityManagerInterface $entityManager
     * @param \App\Services\UserServiceInterface $userService
     */
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly UserServiceInterface $userService,
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

        $expense->setUser($expenseTransfer->user);
        $expense->setCategory($expenseTransfer->category);
        $expense->setAmount($expenseTransfer->amount);
        $expense->setDescription($expenseTransfer->description);

        $this->entityManager->persist($expense);
        $this->entityManager->flush();

        $this->userService->updateBalance($expenseTransfer->user, -1 * $expense->getAmount());

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
            ->createQueryBuilder('e')
            ->select('e', 'c', 'u')
            ->leftJoin('e.category', 'c')
            ->leftJoin('e.user', 'u')
            ->where('u.id = :user_id')
            ->setParameters(['user_id' => $userTransfer->getId()])
            ->setFirstResult($paginateTransfer->start)
            ->setMaxResults($paginateTransfer->size)
            ->getQuery()
            ->getArrayResult();
    }
}
