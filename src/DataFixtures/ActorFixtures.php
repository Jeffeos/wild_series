<?php

namespace App\DataFixtures;

use App\Entity\Actor;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker;
use App\Service\Slugify;

class ActorFixtures extends Fixture implements DependentFixtureInterface
{
    public function getDependencies()
    {
        return [ProgramFixtures::class];
    }

    public function load(ObjectManager $manager)
    {
        $slugify = new Slugify();

        for ($i = 1; $i <= 10; $i++) {
            $actor = new Actor();
            $actor->setName('Nom d\'acteur' . $i);
            $slug = $slugify->generate($actor->getName());
            $actor->setSlug($slug);
            $manager->persist($actor);
            $actor->addProgram($this->getReference("program_0"));
            $i++;
        }

        $faker  =  Faker\Factory::create('en_US');

        // one iteration per program
        for ($p=0; $p < 6 ; $p++)
        {
            // one iteration per actor
            for ($i=0; $i < 10; $i++)
            {
                $actor = new Actor();
                $actor->setName($faker->name);
                $slug = $slugify->generate($actor->getName());
                $actor->setSlug($slug);
                $actor->addProgram($this->getReference('program_'.$p));
                $manager->persist($actor);
            }
        }

        $manager->flush();

    }
}