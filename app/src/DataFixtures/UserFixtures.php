<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public const EXAMPLE_USER_REFERENCE = 'EXAMPLE_USER_REFERENCE';

    /**
     * @param \Doctrine\Persistence\ObjectManager $manager
     *
     * @return void
     */
    public function load(ObjectManager $manager): void
    {
        $exampleUser = new User();
        $exampleUser->setEmail("example@example.com");
        $exampleUser->setBalance(1000);
        $exampleUser->setPassword(hash('sha256', "password"));

        $manager->persist($exampleUser);
        $manager->flush();

        $this->setReference(self::EXAMPLE_USER_REFERENCE, $exampleUser);
    }
}
