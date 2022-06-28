<?php

namespace App\Repository;

use App\Entity\Prestation;
use App\Entity\Commande;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Prestation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Prestation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Prestation[]    findAll()
 * @method Prestation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PrestationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Prestation::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Prestation $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Prestation $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /////////////////////////////////////////
    // Cherche les créneau libre qui sont encore disponible
    // $sortieQuery =si false alors retourne un array sinon retourne un queryBuilder
    // $tous = si true prend uniquement les dates qui sont dans le future
    /////////////////////////////////////////

    /**
     * @return Prestation[] Returns an array of Prestation objects
     */
    public function getCreneau($getResult = true, $libre = true, $passe = false , $indispo = true)
    {
        // prend la date d'aujourd'hui
        if ($indispo) {
            $d = new \DateTime('now', new \DateTimeZone('Europe/Paris'));
        }else {
            $d = new \DateTime('now +12 hour', new \DateTimeZone('Europe/Paris'));
        }

        $em = $this->getEntityManager();
        $sub = $em->createQueryBuilder();

        $qb = $sub;
        $qb->select('p')
            ->from('App\Entity\Prestation', 'p')
            ->leftJoin('p.commandes', 'co')
            ->where('co.id != :id');

        $sub = $em->createQueryBuilder();
        $sub->select('pr')
            ->from('App\Entity\Prestation', 'pr');
        
        switch ($libre) {
            case true:
                $sub->where($sub->expr()->notIn('pr.id', $qb->getDQL()));
                break;
            case false:
                $sub->where($sub->expr()->in('pr.id', $qb->getDQL()));
                break;
        }

        switch ($passe) {
            case true:
                $sub->andWhere('pr.debut < :today')
                ->setParameter('today', $d);
                break;
            case false:
                $sub->andWhere('pr.debut > :today')
                ->setParameter('today', $d);
                break;
        }
        
        $sub->setParameter('id', 'p.id')
            ->orderby('pr.debut');
        if ($getResult) {
            $query = $sub->getQuery();

            return $query->getResult();
        } else {
            return $sub;
        }
    }
    

    // /**
    //  * @return Prestation[] Returns an array of Prestation objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Prestation
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
