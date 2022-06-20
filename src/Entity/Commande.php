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
     * @ORM\JoinColumn(nullable=false)
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
     * @ORM\Column(type="boolean")
     */
    private $paye;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $lien_pdf;

    /**
     * @ORM\OneToMany(targetEntity=Typage::class, mappedBy="commande" ,cascade={"persist"})
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

    public function getLienPdf(): ?string
    {
        return $this->lien_pdf;
    }

    public function setLienPdf(string $lien_pdf): self
    {
        $this->lien_pdf = $lien_pdf;

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
}
