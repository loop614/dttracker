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
        $user = $this->userService->getUserById($expenseTransfer->getUserId());
        $expense->setUser($user);
        $expense->setAmount($expenseTransfer->getAmount());
        $expense->setDescription($expenseTransfer->getDescription());

        $this->entityManager->persist($expense);
        $this->entityManager->flush();

        $this->userService->updateBalance($user, $expenseTransfer->getAmount());

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
            ->createQueryBuilder('iqb')
            ->select('iqb', 'u')
            ->leftJoin('iqb.user', 'u')
            ->where('u.id = :user_id')
            ->setParameters(['user_id' => $userTransfer->getId()])
            ->setFirstResult($paginateTransfer->getStart())
            ->setMaxResults($paginateTransfer->getSize())
            ->getQuery()
            ->getArrayResult();
    }
}
