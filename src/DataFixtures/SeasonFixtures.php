<?php


namespace App\DataFixtures;

use App\Entity\Season;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker;

class SeasonFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');

        // one iteration per programs
        for ($p=0; $p < 6; $p++)
        {
            // one iteration per season
            for ($i=1; $i <= 10; $i++)
            {
                $season = new Season();
                $season->setDescription($faker->text($maxNbChars = 200));
                $season->setYear(rand(1980, 2020));
                $season->setProgram($this->getReference('program_'.$p));
                $season->setNumber($i);
                // add ref to be used on EpisodeFixtures generation
                $this->addReference("season_" . $p . $i, $season);

                $manager->persist($season);
            }
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [ProgramFixtures::class];
    }
}