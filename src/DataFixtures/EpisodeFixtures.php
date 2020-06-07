<?php


namespace App\DataFixtures;

use App\Entity\Episode;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker;

class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');

        // one iteration per programs
        for ($p=0; $p < 6; $p++)
        {
            // one iteration per seasons
            for ($i=1; $i <= 10; $i++)
            {
                // one iteration per episode
                for ($j=1; $j <= 10; $j++)
                {
                    $episode = new Episode();
                    $episode->setSeason($this->getReference('season_'.$p.$i));
                    $episode->setTitle($faker->sentence(4, true));
                    $episode->setNumber($j);
                    $episode->setSynopsis($faker->text($maxNbChars = 200));

                    $manager->persist($episode);
                }
            }
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [SeasonFixtures::class];
    }
}