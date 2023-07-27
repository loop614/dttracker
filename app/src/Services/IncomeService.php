<?php

declare(strict_types=1);

namespace App\Services;

use App\Entity\Income;
use App\Transfer\IncomeTransfer;
use App\Transfer\PaginateTransfer;
use App\Transfer\UserTransfer;
use Doctrine\ORM\EntityManagerInterface;

final class IncomeService implements IncomeServiceInterface
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
     * @param \App\Transfer\IncomeTransfer $expenseTransfer
     *
     * @return \App\Entity\Income
     */
    public function create(IncomeTransfer $expenseTransfer): Income
    {
        $expense = new Income();

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
    public function getIncomesByUser(UserTransfer $userTransfer, PaginateTransfer $paginateTransfer): array
    {
        return $this->entityManager
            ->getRepository(Income::class)
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
