<?php

// model de transformation d'un objet sesssion en son identifiant ID

namespace App\Form\DataTransformer;

use App\Entity\Commande;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class CommandeTransformer implements DataTransformerInterface
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function transform($commande)
    {
        if (null === $commande) {
            return '';
        }

        return $commande->getId();
    }

    public function reverseTransform($commandeId)
    {
        if (!$commandeId) {
            return;
        }
        $commande = $this->entityManager->getRepository(Commande::class)->find($commandeId);
        if (null === $commandeId) {
            throw new TransformationFailedException(sprintf(
                'An issue with number "%s" does not exist!',
                $commandeId
            ));
        }

        return $commande;
    }
}