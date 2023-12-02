<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
   
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'category', targetEntity: Program::class)]
    private $programs;

    public function __construct()
    {
    $this->programs = new ArrayCollection();
    }

    //ajout du getter de cet attribut:
    public function getPrograms(): Collection
    {
    return $this->programs;
    }

    //méthode pour ajouter une série:
    public function addProgram(Program $program): self
    {
    if (!$this->programs->contains($program)) {
        $this->programs->add($program);
        $program->setCategory($this);
    }

    return $this;
    }

    //ajout d'une méthode pour supprimer une série :
    public function removeProgram(Program $program): self
    {
    if ($this->programs->removeElement($program)) {
        // set the owning side to null (unless already changed)
        if ($program->getCategory() === $this) {
            $program->setCategory(null);
        }
    }

    return $this;
    }

    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }
}
