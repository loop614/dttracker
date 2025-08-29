<?php

declare(strict_types=1);

namespace App\Services;

use App\Entity\Income;
use App\Transfer\IncomeFilterTransfer;
use App\Transfer\IncomeTransfer;
use App\Transfer\PaginateTransfer;
use App\Transfer\UserTransfer;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;

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
     * @param \App\Transfer\IncomeTransfer $incomeTransfer
     *
     * @return \App\Entity\Income
     */
    public function create(IncomeTransfer $incomeTransfer): Income
    {
        $income = new Income();
        $user = $this->userService->getUserById($incomeTransfer->getUserId());
        $income->setUser($user);
        $income->setAmount($incomeTransfer->getAmount());
        $income->setDescription($incomeTransfer->getDescription());
        $income->updateTimestamps();

        $this->entityManager->persist($income);
        $this->entityManager->flush();

        $this->userService->updateBalance($user, $incomeTransfer->getAmount());

        return $income;
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

    /**
     * @param \App\Transfer\IncomeFilterTransfer $incomeFilterTransfer
     * @param \App\Transfer\PaginateTransfer $paginateTransfer
     *
     * @return array
     */
    public function filter(IncomeFilterTransfer $incomeFilterTransfer, PaginateTransfer $paginateTransfer): array
    {
        $query =  $this->entityManager
            ->getRepository(Income::class)
            ->createQueryBuilder('eqb')
            ->select('eqb', 'u')
            ->leftJoin('eqb.user', 'u')
            ->where('u.id = :user_id');

        $this->filterOptional($incomeFilterTransfer, $query);

        $query->setParameter('user_id', $incomeFilterTransfer->getUserId())
            ->setFirstResult($paginateTransfer->getStart())
            ->setMaxResults($paginateTransfer->getSize());

        return $query->getQuery()->getArrayResult();
    }

    /**
     * @param \App\Transfer\IncomeFilterTransfer $incomeFilterTransfer
     *
     * @return array
     */
    public function aggregate(IncomeFilterTransfer $incomeFilterTransfer): array
    {
        $query = $this->entityManager
            ->getRepository(Income::class)
            ->createQueryBuilder('eqb')
            ->select('sum(eqb.amount) as amount_sum', 'eqb', 'u')
            ->leftJoin('eqb.user', 'u')
            ->where('u.id = :user_id');

        $this->filterOptional($incomeFilterTransfer, $query);
        $query->addGroupBy('u.id');
        $query->addGroupBy('eqb.id');
        $query->setParameter('user_id', $incomeFilterTransfer->getUserId());

        return $query->getQuery()->getArrayResult();
    }

    /**
     * @param \App\Transfer\IncomeFilterTransfer $incomeFilterTransfer
     * @param \Doctrine\ORM\QueryBuilder $query
     *
     * @return void
     */
    private function filterOptional(IncomeFilterTransfer $incomeFilterTransfer, QueryBuilder $query): void
    {
        if ($incomeFilterTransfer->getAmountGrater() !== null) {
            $query
                ->andWhere('eqb.amount > :amountGrater')
                ->setParameter("amountGrater", $incomeFilterTransfer->getAmountGrater());
        }

        if ($incomeFilterTransfer->getAmountLess() !== null) {
            $query
                ->andWhere('eqb.amount < :amountLess')
                ->setParameter("amountLess", $incomeFilterTransfer->getAmountLess());
        }

        if ($incomeFilterTransfer->getStartDate() !== null) {
            $query
                ->andWhere('eqb.createdAt > :start_date')
                ->setParameter("start_date", $incomeFilterTransfer->getStartDate());
        }

        if ($incomeFilterTransfer->getEndDate() !== null) {
            $query
                ->andWhere('eqb.createdAt < :end_date')
                ->setParameter("end_date", $incomeFilterTransfer->getEndDate());
        }
    }
}
