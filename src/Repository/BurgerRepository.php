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

    public function findBurgersWithIngredient(string $ingredient): array
    {
        return $this->createQueryBuilder('b')
        ->innerJoin('b.pain', 'p')
        ->innerJoin('b.oignon', 'o')
        ->innerJoin('b.sauce', 's')
        ->where('o.nom = :ingredient OR s.nom = :ingredient OR p.nom = :ingredient')
        ->setParameter('ingredient', $ingredient)
        ->getQuery()
        ->getResult();
    }

    public function findTopXBurgers(int $limit): array
    {
        return $this->createQueryBuilder('b')
            ->orderBy('b.prix', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function findBurgersWithoutIngredient(string $ingredient): array
    {
       return $this->createQueryBuilder('b')
        ->innerJoin('b.pain', 'p')
        ->innerJoin('b.oignon', 'o')
        ->innerJoin('b.sauce', 's')
        ->where('o.nom != :ingredient OR s.nom != :ingredient OR p.nom != :ingredient')
        ->setParameter('ingredient', $ingredient)
        ->getQuery()
        ->getResult();
    }

    public function findBurgersWithMinimumIngredients(int $minIngredients): array
    {
        return $this->createQueryBuilder('b')
        ->leftJoin('b.pain', 'p')
        ->leftJoin('b.oignon', 'o')
        ->leftJoin('b.sauce', 's')
        ->addSelect('COUNT(DISTINCT o.id) + COUNT(DISTINCT s.id) + COUNT(DISTINCT p.id) AS HIDDEN nbIngredients')
        ->groupBy('b.id')
        ->having('nbIngredients >= :minIngredients')
        ->setParameter('minIngredients', $minIngredients)
        ->getQuery()
        ->getResult();
    }
}
