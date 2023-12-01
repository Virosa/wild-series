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
    ['title' => 'Kingdom', 'synopsis' => 'Des zombies en Corée', 'category' =>'Action'],
    ['title' => 'Le dernier des Mohicans', 'synopsis' => 'Les indiens en Amérique', 'category' =>'Aventure'],
    ['title' => 'Retour vers le Futur', 'synopsis' => 'Voyage dans le futur proche', 'category' =>'Aventure'],
    ['title' => 'Indiana Jones et le temple maudit', 'synopsis' => 'Archéologue aventureux', 'category' =>'Aventure'],
    ['title' => 'Le Titanic', 'synopsis' => 'Histoire vraie du naufrage', 'category' =>'Aventure'],
    ];
    
    public function load(ObjectManager $manager)
    {
        foreach (self::PROGRAM as $program){
        $newProgram = new Program();
        $newProgram->setTitle($program['title']);
        $newProgram->setSynopsis($program['synopsis']);

        $newProgram->setCategory($this->getReference('category_' . $program['category']));

        $manager->persist($newProgram);

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
