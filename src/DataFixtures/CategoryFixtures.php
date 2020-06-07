<?php


namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    const CATEGORIES = [
        'Action',
        'Aventure',
        'Animation',
        'Fantastique',
        'Horreur',
    ];

    // two exemples of load function (should be named load to work
    // command to execute load : php bin/console doctrine:fixtures:load
    public function load2(ObjectManager $manager)
    {
        for ($i = 1; $i <= 50; $i++) {
            $category = new Category();
            $category->setName('Nom de catégorie ' . $i);
            $manager->persist($category);
        }
        $manager->flush();
    }

    public function load(ObjectManager $manager)
    {
            foreach (self::CATEGORIES as $key=> $categoryName)
            {
                $category = new Category();
                $category->setName($categoryName);

                $manager->persist($category);
                $this->addReference('categorie_' . $key, $category);
            }

            $manager->flush();
    }
}