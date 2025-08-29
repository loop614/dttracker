<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Services\ExpenseServiceInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ExpenseFixtures extends Fixture implements DependentFixtureInterface
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
    }

    /**
     * @return string[]
     */
    public function getDependencies()
    {
        return [
            UserFixtures::class,
        ];
    }
}
