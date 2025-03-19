<?php

namespace App\Repository;

use App\Entity\Ejemplo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Ejemplo>
 */
class EjemploRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ejemplo::class);
    }

    //    /**
    //     * @return Ejemplo[] Returns an array of Ejemplo objects
    //     */
    
    public function findByName($value): array
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.nombre = :name')
            ->setParameter('name', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

    //hace lo mismo que la función anterior
    public function findByNameQuery($value): array
    {

        $em = $this->getEntityManager();
        
        $query = $em->createQuery('SELECT u FROM App\Entity\Ejemplo u WHERE u.nombre = :name')
        ->setParameter('name', $value); // Corrected parameter binding

        return $query->getResult();

    }

    //    public function findOneBySomeField($value): ?Ejemplo
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
