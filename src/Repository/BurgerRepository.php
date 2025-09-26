<?php

namespace App\Repository;

use App\Entity\Burger;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Burger>
 */
class BurgerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Burger::class);
    }

    public function findBurgersWithIngredient(string $ingredient)
    {
        $dql = "SELECT b
        FROM App\Entity\Burger b
        LEFT JOIN b.oignon o
        LEFT JOIN b.pain p
        LEFT JOIN b.sauce s
        WHERE o.name = :ingredient
           OR p.name = :ingredient
           OR s.name = :ingredient";
        return $this->getEntityManager()
            ->createQuery($dql)
            ->setParameter('ingredient', $ingredient)
            ->getResult();
    }

    public function findTopXBurgers(int $limite)
    {

        $dql = "SELECT b
        FROM App\Entity\Burger b
        ORDER BY b.price DESC";
        return $this->getEntityManager()
            ->createQuery($dql)
            ->setMaxResults($limite)
            ->getResult();
    }

    public function findBurgersWithoutIngredient(string $ingredient): array
    {
        $qb = $this->createQueryBuilder('b')
            ->leftJoin('b.oignon', 'o')
            ->leftJoin('b.pain', 'p')
            ->leftJoin('b.sauce', 's')
            ->where('o.name != :ingredient')
            ->andWhere('p.name != :ingredient')
            ->andWhere('s.name != :ingredient')
            ->setParameter('ingredient', $ingredient)
            ->getQuery();

        return $qb->getResult();
    }

    //    /**
    //     * @return Burger[] Returns an array of Burger objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('b.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Burger
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
