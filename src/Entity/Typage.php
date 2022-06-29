<?php

namespace App\Entity;

use App\Repository\TypageRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TypageRepository::class)
 */
class Typage
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $nb_couteau;

    /**
     * @ORM\ManyToOne(targetEntity=Commande::class, inversedBy="typages",cascade={"persist"})
     */
    private $commande;

    /**
     * @ORM\ManyToOne(targetEntity=TypeCouteau::class, inversedBy="typages")
     */
    private $typeCouteau;
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNbCouteau(): ?int
    {
        return $this->nb_couteau;
    }

    public function setNbCouteau(int $nb_couteau): self
    {
        $this->nb_couteau = $nb_couteau;

        return $this;
    }

    public function getCommande(): ?Commande
    {
        return $this->commande;
    }

    public function setCommande(?Commande $commande): self
    {
        $this->commande = $commande;

        return $this;
    }

    public function getTypeCouteau(): ?TypeCouteau
    {
        return $this->typeCouteau;
    }

    public function setTypeCouteau(?TypeCouteau $typeCouteau): self
    {
        $this->typeCouteau = $typeCouteau;

        return $this;
    }

    public function __toString(){
        
        return $this->getId();
    }
    /////////////////////////////////////////////////////
    // Nouvelle méthode
    /////////////////////////////////////////////////////


    // avoir le total de chaque type de couteau
    public function getTotalType(){

        $unite = $this->getTypeCouteau()->getTarif();

        return $this->getNbCouteau() * $unite;
    }
}
