<?php

namespace App\Repository;

use App\Entity\Prestation;
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
    // Cherche les créneau libre qui ne sont pas encore pris par des commandes
    /////////////////////////////////////////

    /**
     * @return Prestation[] Returns an array of Prestation objects
     */
    public function getCreneauLibre($sortieQuery = false)
    {
        // prend la date d'aujourd'hui
        $d = new \DateTime();
        $d->modify('+2 hours');

        $em = $this->getEntityManager();
        $sub = $em->createQueryBuilder();

        $qb = $sub;
        $qb->select('p')
            ->from('App\Entity\Prestation', 'p')
            ->leftJoin('p.commandes', 'co')
            ->where('co.id != :id');

        $sub = $em->createQueryBuilder();
        $sub->select('pr')
            ->from('App\Entity\Prestation', 'pr')
            ->where($sub->expr()->notIn('pr.id', $qb->getDQL()))
            ->andWhere('pr.debut > :today')
            ->setParameter('today', $d)
            ->setParameter('id', 'p.id')
            ->orderby('pr.debut');
        //////////////////////////////////////////
        // Si je met true en paratmetre la methode getCreneauLibre() alors elle me retourne un query builder
        // si non c'est false par défaut et me retourne un array
        //////////////////////////////////////////
        if (!$sortieQuery) {
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
