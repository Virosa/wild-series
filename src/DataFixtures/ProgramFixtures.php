<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Program;
;

class ProgramFixtures extends Fixture implements DependentFixtureInterface
{
    public const PROGRAM =[
    ['title' => 'Breaking Bad', 
    'synopsis' => 'Un professeur de chimie de lycée chez qui 
    on a diagnostiqué un cancer du poumon inopérable se tourne vers la fabrication et la 
    vente de méthamphétamine pour assurer l\'avenir de sa famille.', 
    'country' => 'Etats Unis',
    'year' => 2009,
    'category' =>'Action'],

    ['title' => 'Kingdom', 
    'synopsis' => 'Un prince héritier est envoyé en mission suicide 
    pour enquêter sur une mystérieuse épidémie.', 
    'country' => 'Corée du Sud',
    'year' => 2019,
    'category' =>'Action'],

    ['title' => 'The Wire', 
    'synopsis' => 'Le monde de la drogue à Baltimore à travers
    les yeux des trafiquants comme des forces de l\'ordre.', 
    'country' => 'Etats Unis',
    'year' => 2002,
    'category' =>'Action'],

    ];
    
    public function load(ObjectManager $manager)
    {
        foreach (self::PROGRAM as $program){
        $newProgram = new Program();
        $newProgram->setTitle($program['title']);
        $newProgram->setSynopsis($program['synopsis']);
        $newProgram->setCountry($program['country']);
        $newProgram->setYear($program['year']);
        $newProgram->setCategory($this->getReference('category_' . $program['category']));
        //$this->addReference('program_'. $program['title'], $program); 
        $manager->persist($newProgram);
        $this->addReference('program_' . $program['title'], $newProgram);    
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
