<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture implements DependentFixtureInterface
{
    public const FOOD_CATEGORY = 'FOOD_CATEGORY';

    private const EXAMPLE_CATEGORIES = ["car", "accommodation", "gifts"];

    /**
     * @param \Doctrine\Persistence\ObjectManager $manager
     *
     * @return void
     */
    public function load(ObjectManager $manager)
    {
        $user = $this->getReference(UserFixtures::EXAMPLE_USER_REFERENCE);
        $category = new Category();
        $category->setName("food");
        $category->setUser($user);
        $manager->persist($category);
        $manager->flush();
        $this->setReference(self::FOOD_CATEGORY, object: $category);

        foreach (self::EXAMPLE_CATEGORIES as $exampleCategory) {
            $category = new Category();
            $category->setName($exampleCategory);
            $category->setUser($user);
            $manager->persist($category);
        }

        $manager->flush();
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
