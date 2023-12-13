<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker\Factory;
use App\Entity\Actor;



class ActorFixture extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker=Factory::create();

        for ($i = 0; $i < 10; $i++){
            $programs = ProgramFixtures::getTitles();
            $programsRandKeys = array_rand($programs, 3);
            $actor = new Actor();
            $actor->setName($faker->name());
            foreach ($programsRandKeys as $title) {
                $actor->addProgram($this->getReference('program_' . $programs[$title]));
            }
            $manager->persist($actor);
        }
        $manager->flush();
    }

    public function getDependencies() : array
    {
        return [
            ProgramFixtures::class,
        ];
    }
}
