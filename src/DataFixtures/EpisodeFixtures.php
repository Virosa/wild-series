<?php

namespace App\DataFixtures;

use App\Entity\Season;
use App\Entity\Program;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Episode;
;

class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{
    public const EPISODE =[
    [
        'program'=> 'Breaking Bad',
        'season' => 1,
        'title' => 'Pilot', 
        'number' => 1, 
        'synopsis' =>'Diagnosed with terminal lung cancer, 
        chemistry teacher Walter White teams up with former student
        Jesse Pinkman to cook and sell crystal meth.'],

    [   'program'=> 'Breaking Bad',
        'season' => 1,
        'title' => 'Cat\'s in the Bag', 
        'number' => 2, 
        'synopsis' =>'After their first drug deal goes terribly wrong, 
        Walt and Jesse are forced to deal with a corpse and a prisoner.
        Meanwhile, Skyler grows suspicious of Walt\'s activities.'],
    
    [   'program'=> 'Breaking Bad',
        'season' => 1,
        'title' => 'And the Bag is in the River', 
        'number' => 3, 
        'synopsis' =>'Walt and Jesse clean up after the bathtub incident
        before Walt decides what course of action to take with their prisoner
        Krazy-8.'],
    
    [   'program'=> 'Breaking Bad',
        'season' => 1,
        'title' => 'Cancer man', 
        'number' => 4, 
        'synopsis' =>'Walt tells the rest of his family about his cancer. 
        Jesse tries to make amends with his own parents.'],
    
    [   'program'=> 'Breaking Bad',
        'season' => 1,
        'title' => 'Gray Matter', 
        'number' => 5, 
        'synopsis' =>'Walt rejects everyone who tries to help him with the cancer. 
        Jesse tries his best to create Walt\'s meth, with the help of an old 
        friend.'],
    ];
    
    public function load(ObjectManager $manager)
    {
        foreach (self::EPISODE as $episodeNumber){
        $episode = new Episode();
        $episode->setTitle($episodeNumber['title']);
        $episode->setNumber($episodeNumber['number']);
        $episode->setSeason($this->getReference('program_'. $episodeNumber['program'].'season_' . $episodeNumber['season']));
        $episode->setSynopsis($episodeNumber['synopsis']);
           
 
        $manager->persist($episode);

    }
        $manager->flush();
   
    }
    
    public function getDependencies()
    {
        // Tu retournes ici toutes les classes de fixtures dont ProgramFixtures dépend
        return [
          SeasonFixtures::class,
        ];
    }
}