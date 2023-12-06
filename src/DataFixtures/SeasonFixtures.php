<?php

namespace App\DataFixtures;

use App\Entity\Season;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

//Tout d'abord nous ajoutons la classe Factory de FakerPhp
use Faker\Factory;

class SeasonFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        foreach(ProgramFixtures::getTitles() as $program){
             $year = $faker->year();
            for($i = 1; $i < 6; $i++) {
                $season = new Season();
                $season->setNumber($i);
                $season->setYear(intval($year) + $i);
                $season->setDescription($faker->realTextBetween(160, 320, 3));
                $season->setProgram($this->getReference('program_' . $program));
                $manager->persist($season);
                $this->addReference('program_' . $program . 'season_' . $i, $season);
            }
        }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
           ProgramFixtures::class,
        ];
    }
}