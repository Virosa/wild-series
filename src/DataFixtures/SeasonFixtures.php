<?php

namespace App\DataFixtures;

use App\Entity\Episode;
use App\Entity\Program;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Season;
;

class SeasonFixtures extends Fixture implements DependentFixtureInterface
{
    public const SEASON =[
    [
        'title' => 'Breaking Bad',
        'number' => 1, 
        'year' => '2009', 
        'description' =>'Diagnosed with terminal lung cancer, 
        chemistry teacher Walter White teams up with former student
        Jesse Pinkman to cook and sell crystal meth.'
    ],

    ['title' => 'Breaking Bad',
    'number' => 2, 'year' => '2010',
    'description' =>'Walt and Jesse realize how dire their situation is.
    They must come up with a plan to kill Tuco before Tuco kills them first.'],
    
    ['title' => 'Breaking Bad',
    'number' => 3, 'year' => '2010', 
    'description' =>'Skyler goes through with her plans to divorce Walt.
    Jesse finishes rehab.'],
    
    ['title' => 'Breaking Bad',
    'number' => 4, 'year' => '2011', 
    'description' =>'Walt and Jesse are held captive by Gus, after Gale\'s death.
    Meanwhile, Skyler tries to find out what happened to Walt.'],
    
    ['title' => 'Breaking Bad',
    'number' => 5, 'year' => '2012',
    'description' =>'Now that Gus is dead, Walt, Jesse, and Mike work to cover their tracks.
    Skyler panics when Ted Beneke wakes up.'],
    ];
    
    public function load(ObjectManager $manager)
    {
        foreach (self::SEASON as $seasonNumber) {
            $season = new Season();
            $season->setNumber($seasonNumber['number']);
            $season->setProgram($this->getReference('program_' . $seasonNumber['title']));
            $season->setYear($seasonNumber['year']);
            $season->setDescription($seasonNumber['description']);
            $manager->persist($season);
            $this->addReference('program_' . $seasonNumber['title'].'season_' . $seasonNumber['number'], $season);
        }
        $manager->flush();
   
    }
    
    public function getDependencies()
    {
        // Tu retournes ici toutes les classes de fixtures dont ProgramFixtures dépend
        return [
          ProgramFixtures::class,
        ];
    }
}