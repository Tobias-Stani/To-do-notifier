<?php

namespace App\Repository;

use App\Entity\Cronometro;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Cronometro>
 */
class CronometroRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cronometro::class);
    }

    //    /**
    //     * @return Cronometro[] Returns an array of Cronometro objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Cronometro
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function save(Cronometro $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);
    
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findLastFiveByMateria($materiaId): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.materia = :materiaId')
            ->setParameter('materiaId', $materiaId)
            ->orderBy('c.id', 'DESC') // Ordenar por ID en orden descendente
            ->setMaxResults(5) // Limitar a los últimos 5 registros
            ->getQuery()
            ->getResult();
    }


}
