<?php

declare(strict_types=1);

namespace App\Services;

use App\Entity\Category;
use App\Entity\User;
use App\Transfer\CategoryTransfer;
use App\Transfer\PaginateTransfer;
use App\Transfer\UserTransfer;
use Doctrine\ORM\Tools\Pagination\Paginator;

interface CategoryServiceInterface
{
    /**
     * @param \App\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return \App\Entity\Category
     */
    public function create(CategoryTransfer $categoryTransfer): Category;

    /**
     * @param \App\Transfer\UserTransfer $userTransfer
     * @param \App\Transfer\PaginateTransfer $paginateTransfer
     *
     * @return array
     */
    public function getCategoriesByUser(UserTransfer $userTransfer, PaginateTransfer $paginateTransfer): array;

    /**
     * @param int $categoryId
     *
     * @return \App\Entity\Category|null
     */
    public function getCategoryById(int $categoryId): ?Category;
}
