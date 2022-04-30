<?php

namespace App\Entity;

use App\Repository\CommandeRepository;
use Doctrine\ORM\Mapping as ORM;

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
     * @ORM\Column(type="integer")
     */
    private $nb_couteau;

    /**
     * @ORM\ManyToOne(targetEntity=Prestation::class, inversedBy="commandes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $prestation;

    /**
     * @ORM\ManyToOne(targetEntity=user::class, inversedBy="commandes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $client;

    public function getNbCouteau(): ?int
    {
        return $this->nb_couteau;
    }

    public function setNbCouteau(int $nb_couteau): self
    {
        $this->nb_couteau = $nb_couteau;

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

    public function getClient(): ?user
    {
        return $this->client;
    }

    public function setClient(?user $client): self
    {
        $this->client = $client;

        return $this;
    }
}
