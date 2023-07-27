<?php

declare(strict_types=1);

namespace App\Services;

use App\Entity\Category;
use App\Transfer\CategoryTransfer;
use App\Transfer\PaginateTransfer;
use App\Transfer\UserTransfer;
use Doctrine\ORM\EntityManagerInterface;

final class CategoryService implements CategoryServiceInterface
{
    /**
     * @param \Doctrine\ORM\EntityManagerInterface $entityManager
     * @param \App\Services\UserServiceInterface $userService
     */
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly UserServiceInterface   $userService,
    ) {
    }

    /**
     * @param \App\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return \App\Entity\Category
     */
    public function create(CategoryTransfer $categoryTransfer): Category
    {
        $category = new Category();

        $category->setUser($this->userService->getUserById($categoryTransfer->getUserId()));
        $category->setName($categoryTransfer->getName());

        $this->entityManager->persist($category);
        $this->entityManager->flush();

        return $category;
    }

    /**
     * @param \App\Transfer\UserTransfer $userTransfer
     * @param \App\Transfer\PaginateTransfer $paginateTransfer
     *
     * @return array
     */
    public function getCategoriesByUser(UserTransfer $userTransfer, PaginateTransfer $paginateTransfer): array
    {
        return $this->entityManager
            ->getRepository(Category::class)
            ->createQueryBuilder('cqb')
            ->innerJoin('cqb.user', 'u')
            ->where('u.id = :user_id')
            ->setParameters(['user_id' => $userTransfer->getId()])
            ->setFirstResult($paginateTransfer->getStart())
            ->setMaxResults($paginateTransfer->getSize())
            ->getQuery()
            ->getArrayResult();
    }

    /**
     * @param int $categoryId
     *
     * @return \App\Entity\Category|null
     */
    public function getCategoryById(int $categoryId): ?Category
    {
        return $this->entityManager
            ->getRepository(Category::class)
            ->createQueryBuilder('cqb')
            ->select('cqb')
            ->where('cqb.id', ':category_id')
            ->setParameters(['category_id', $categoryId])
            ->getQuery()
            ->getOneOrNullResult();
    }
}
