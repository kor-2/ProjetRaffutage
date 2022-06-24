<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use App\Repository\CommandeRepository;

/**
 * @ORM\Entity(repositoryClass=CommandeRepository::class)
 */
class Commande
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="commandes")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Prestation::class, inversedBy="commandes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $prestation;

    /**
     * @ORM\Column(type="date")
     */
    private $date_facturation;

    /**
     * @ORM\OneToOne(targetEntity=Facture::class, inversedBy="commande", cascade={"persist", "remove"})
     */
    private $facture;
    
    /**
     * @ORM\OneToMany(targetEntity=Typage::class, mappedBy="commande" ,cascade={"persist"} ,orphanRemoval=true)
     */
    private $typages;

    /**
     * @ORM\Column(type="json")
     */
    private $details = [];

   

    public function __construct()
    {
        $this->typages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getPrestation(): ?Prestation
    {
        return $this->prestation;
    }

    public function setPrestation(?Prestation $prestation): self
    {
        $this->prestation = $prestation;

        return $this;
    }

    public function getDateFacturation(): ?\DateTimeInterface
    {
        return $this->date_facturation;
    }

    public function setDateFacturation(\DateTimeInterface $date_facturation): self
    {
        $this->date_facturation = $date_facturation;

        return $this;
    }

    public function getPaye(): ?bool
    {
        return $this->paye;
    }

    public function setPaye(bool $paye): self
    {
        $this->paye = $paye;

        return $this;
    }

    public function getFacture(): ?Facture
    {
        return $this->facture;
    }

    public function setFacture(?Facture $facture): self
    {
        $this->facture = $facture;

        return $this;
    }

    /**
     * @return Collection<int, Typage>
     */
    public function getTypages(): Collection
    {
        return $this->typages;
    }

    public function addTypage(Typage $typage): self
    {
        if (!$this->typages->contains($typage)) {
            $this->typages[] = $typage;
            $typage->setCommande($this);
        }

        return $this;
    }

    public function removeTypage(Typage $typage): self
    {
        if ($this->typages->removeElement($typage)) {
            // set the owning side to null (unless already changed)
            if ($typage->getCommande() === $this) {
                $typage->setCommande(null);
            }
        }

        return $this;
    }
    
    public function getDetails(): ?array
    {
        return $this->details;
    }

    public function setDetails(array $details): self
    {
        $this->details = $details;

        return $this;
    }

    
    
    /////////////////////////////////////////////////////
    // Nouvelles méthodes
    /////////////////////////////////////////////////////

    // avoir le total de la commande
    public function getTotal(){

        $types = $this->getTypages();

        $total = 0;
        foreach ($types as $type) {
            $total = $total + $type->getTotalType();
        }

        return $total;
    }
    // avoir le total de couteau 

    public function getCouteaux(){
        $types = $this->getTypages();
        $total = 0;
        foreach ($types as $type) {
            $total = $total + $type->getNbCouteau();
        }
        return $total;
    }

    


}
    
