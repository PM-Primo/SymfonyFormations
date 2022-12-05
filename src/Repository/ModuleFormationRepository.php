<?php

namespace App\Repository;

use App\Entity\ModuleFormation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ModuleFormation>
 *
 * @method ModuleFormation|null find($id, $lockMode = null, $lockVersion = null)
 * @method ModuleFormation|null findOneBy(array $criteria, array $orderBy = null)
 * @method ModuleFormation[]    findAll()
 * @method ModuleFormation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ModuleFormationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ModuleFormation::class);
    }

    public function add(ModuleFormation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ModuleFormation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


    public function findNonProgrammes($session_id){

        $em = $this->getEntityManager();
        $sub = $em->createQueryBuilder();

        $qb = $sub;
        $qb->select('mod')
            ->from('App\Entity\ModuleFormation', 'mod')
            ->innerJoin('mod.programmes', 'p')
            ->innerJoin('p.sessionFormation', 'se')
            ->where('se.id = :id')
        ;

        $sub = $em->createQueryBuilder();
        $sub->select('m')
            ->from('App\Entity\ModuleFormation', 'm')
            ->where($sub->expr()->notIn('m.id', $qb->getDQL()))
            ->setParameter('id', $session_id)
            ->orderBy('m.categorie')
        ;

        $query = $sub->getQuery();
        return $query->getResult();

    }

//    /**
//     * @return ModuleFormation[] Returns an array of ModuleFormation objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ModuleFormation
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
