<?php

namespace App\Entity;

use App\Repository\TypeCouteauRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TypeCouteauRepository::class)
 */
class TypeCouteau
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $nom;

    /**
     * @ORM\Column(type="integer")
     */
    private $tarif;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="boolean")
     */
    private $active;

    /**
     * @ORM\OneToMany(targetEntity=Typage::class, mappedBy="typeCouteau")
     */
    private $typages;

    public function __construct()
    {
        $this->typages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getTarif(): ?int
    {
        return $this->tarif;
    }

    public function setTarif(int $tarif): self
    {
        $this->tarif = $tarif;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

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
            $typage->setTypeCouteau($this);
        }

        return $this;
    }

    public function removeTypage(Typage $typage): self
    {
        if ($this->typages->removeElement($typage)) {
            // set the owning side to null (unless already changed)
            if ($typage->getTypeCouteau() === $this) {
                $typage->setTypeCouteau(null);
            }
        }

        return $this;
    }

    public function __toString(){
        return $this->getNom();
    }
}
