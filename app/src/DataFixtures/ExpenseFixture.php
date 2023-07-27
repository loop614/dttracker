<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Expense;
use App\Services\ExpenseServiceInterface;
use App\Transfer\ExpenseTransfer;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ExpenseFixture extends Fixture implements DependentFixtureInterface
{
    /**
     * @param \App\Services\ExpenseServiceInterface $expenseService
     */
    public function __construct(private readonly ExpenseServiceInterface $expenseService) {}

    /**
     * @param \Doctrine\Persistence\ObjectManager $manager
     *
     * @return void
     */
    public function load(ObjectManager $manager)
    {
        $user = $this->getReference(UserFixtures::EXAMPLE_USER_REFERENCE);
        $category = $this->getReference(CategoryFixture::FOOD_CATEGORY);
        $expense = new ExpenseTransfer();
        $expense->setCategoryId($category->getId());
        $expense->setUserId($user->getUserId());
        $expense->setDescription("description");
        $expense->setAmount(150);

        $this->expenseService->create($expense);
    }

    /**
     * @return string[]
     */
    public function getDependencies()
    {
        return [
            UserFixtures::class,
            CategoryFixture::class,
        ];
    }
}
