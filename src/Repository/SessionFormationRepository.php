<?php

namespace App\Repository;

use App\Entity\SessionFormation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SessionFormation>
 *
 * @method SessionFormation|null find($id, $lockMode = null, $lockVersion = null)
 * @method SessionFormation|null findOneBy(array $criteria, array $orderBy = null)
 * @method SessionFormation[]    findAll()
 * @method SessionFormation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SessionFormationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SessionFormation::class);
    }

    public function add(SessionFormation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(SessionFormation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function sessionsPassees()
    {
        $now = new \DateTime();
        return $this->createQueryBuilder('s')
                ->andWhere('s.date_fin < :now')
                ->setParameter('now', $now)
                ->orderBy('s.date_debut', 'ASC')
                ->getQuery()
                ->getResult();
    }

    public function sessionsEnCours()
    {
        $now = new \DateTime();
        return $this->createQueryBuilder('s')
                ->andWhere('s.date_debut <= :now')
                ->andWhere('s.date_fin >= :now')
                ->setParameter('now', $now)
                ->orderBy('s.date_debut', 'ASC')
                ->getQuery()
                ->getResult();
    }

    public function sessionsAVenir()
    {
        $now = new \DateTime();
        return $this->createQueryBuilder('s')
                ->andWhere('s.date_debut > :now')
                ->setParameter('now', $now)
                ->orderBy('s.date_debut', 'ASC')
                ->getQuery()
                ->getResult();
    }

//    /**
//     * @return SessionFormation[] Returns an array of SessionFormation objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?SessionFormation
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
