<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Expense;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ExpenseFixture extends Fixture implements DependentFixtureInterface
{
    /**
     * @param \Doctrine\Persistence\ObjectManager $manager
     *
     * @return void
     */
    public function load(ObjectManager $manager)
    {
        $user = $this->getReference(UserFixtures::EXAMPLE_USER_REFERENCE);
        $category = $this->getReference(CategoryFixture::FOOD_CATEGORY);
        $expense = new Expense();
        $expense->setCategory($category);
        $expense->setUser($user);
        $expense->setDescription("description");
        $expense->setAmount(150);

        $manager->persist($expense);
        $manager->flush();
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
