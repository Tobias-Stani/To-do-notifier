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

    public function findTotalTimeByWeekAndMateria(int $materiaId): array
    {
        // Obtener el inicio y fin de la semana actual
        $inicioSemana = new \DateTime('monday this week');
        $finSemana = new \DateTime('sunday this week');
        $finSemana->setTime(23, 59, 59);

        // Crear la consulta para sumar los tiempos de los cronómetros en la semana actual
        $qb = $this->createQueryBuilder('c')
            ->select('SUM(c.time) as totalTime')
            ->where('c.materia = :materiaId')
            ->andWhere('c.date BETWEEN :inicioSemana AND :finSemana')
            ->setParameter('materiaId', $materiaId)
            ->setParameter('inicioSemana', $inicioSemana)
            ->setParameter('finSemana', $finSemana);

        // Obtener el tiempo total en milisegundos
        $result = $qb->getQuery()->getSingleScalarResult();
        $totalTimeInMilliseconds = $result ? (int) $result : 0;

        // Convertir milisegundos a horas y minutos
        $hours = floor($totalTimeInMilliseconds / (1000 * 60 * 60));
        $minutes = floor(($totalTimeInMilliseconds % (1000 * 60 * 60)) / (1000 * 60));

        // Devolver un array con el total de horas y minutos
        return [
            'hours' => $hours,
            'minutes' => $minutes
        ];
    }

    public function findTotalTimeByDayAndMateria(int $materiaId): array
    {
        // Obtener el inicio y fin del día actual
        $inicioDia = new \DateTime('today');
        $finDia = new \DateTime('today');
        $finDia->setTime(23, 59, 59); // Final del día

        // Crear la consulta para sumar los tiempos de los cronómetros del día actual
        $qb = $this->createQueryBuilder('c')
            ->select('SUM(c.time) as totalTime')
            ->where('c.materia = :materiaId')
            ->andWhere('c.date BETWEEN :inicioDia AND :finDia')
            ->setParameter('materiaId', $materiaId)
            ->setParameter('inicioDia', $inicioDia)
            ->setParameter('finDia', $finDia);

        // Obtener el tiempo total en milisegundos
        $result = $qb->getQuery()->getSingleScalarResult();
        $totalTimeInMilliseconds = $result ? (int) $result : 0;

        // Convertir milisegundos a horas y minutos
        $hours = floor($totalTimeInMilliseconds / (1000 * 60 * 60));
        $minutes = floor(($totalTimeInMilliseconds % (1000 * 60 * 60)) / (1000 * 60));

        // Devolver un array con el total de horas y minutos
        return [
            'hours' => $hours,
            'minutes' => $minutes
        ];
    }

}
