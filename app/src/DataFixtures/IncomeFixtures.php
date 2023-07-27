<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Services\IncomeServiceInterface;
use App\Transfer\IncomeTransfer;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class incomeFixture extends Fixture implements DependentFixtureInterface
{
    /**
     * @param \App\Services\IncomeServiceInterface $incomeService
     */
    public function __construct(private readonly IncomeServiceInterface $incomeService) {}

    /**
     * @param \Doctrine\Persistence\ObjectManager $manager
     *
     * @return void
     */
    public function load(ObjectManager $manager)
    {
        $user = $this->getReference(UserFixtures::EXAMPLE_USER_REFERENCE);
        $income = new IncomeTransfer();
        $income->setUserId($user->getId());
        $income->setDescription("description");
        $income->setAmount(150);

        $this->incomeService->create($income);
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
