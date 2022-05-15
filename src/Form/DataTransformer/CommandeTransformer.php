<?php

// model de transformation d'un objet sesssion en son identifiant ID

namespace App\Form\DataTransformer;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class PrestationTransformer implements DataTransformerInterface
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function transform($session)
    {
        if (null === $session) {
            return '';
        }

        return $session->getId();
    }

    public function reverseTransform($sessionId)
    {
        if (!$sessionId) {
            return;
        }
        $session = $this->entityManager->getRepository(Session::class)->find($sessionId);
        if (null === $sessionId) {
            throw new TransformationFailedException(sprintf(
                'An issue with number "%s" does not exist!',
                $sessionId
            ));
        }

        return $session;
    }
}
