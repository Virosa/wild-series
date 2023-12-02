<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Season;
;

class SeasonFixtures extends Fixture implements DependentFixtureInterface
{
    public const SEASON =[
    ['number' => 1, 'year' => '2009', 
    'description' =>'Diagnosed with terminal lung cancer, 
    chemistry teacher Walter White teams up with former student
    Jesse Pinkman to cook and sell crystal meth.'],

    ['number' => 2, 'year' => '2010',
    'description' =>'Walt and Jesse realize how dire their situation is.
    They must come up with a plan to kill Tuco before Tuco kills them first.'],
    
    ['number' => 3, 'year' => '2010', 
    'description' =>'Skyler goes through with her plans to divorce Walt.
    Jesse finishes rehab.'],
    
    ['number' => 4, 'year' => '2011', 
    'description' =>'Walt and Jesse are held captive by Gus, after Gale\'s death.
    Meanwhile, Skyler tries to find out what happened to Walt.'],
    
    ['number' => 5, 'year' => '2012',
    'description' =>'Now that Gus is dead, Walt, Jesse, and Mike work to cover their tracks.
    Skyler panics when Ted Beneke wakes up.'],
    ];
    
    public function load(ObjectManager $manager)
    {
        foreach (self::SEASON as $seasonNumber){
        $season = new Season();
        $season->setNumber($season['number']);
        $season->setProgram($this->getReference('program_' . $program['title']));
        $season->setYear($season['year']);
        $season->setDescription($season['description']);
        $this->addReference('season_' . $seasonNumber, $season);    

        $manager->persist($season);

    }
        $manager->flush();
   
    }
    
    public function getDependencies()
    {
        // Tu retournes ici toutes les classes de fixtures dont ProgramFixtures dépend
        return [
          CategoryFixtures::class,
        ];
    }
}