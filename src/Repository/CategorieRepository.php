<?php

namespace App\Repository;

use App\Entity\Categorie;
use App\Entity\Programme;
use App\Entity\ModuleFormation;
use App\Entity\SessionFormation;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Categorie>
 *
 * @method Categorie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Categorie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Categorie[]    findAll()
 * @method Categorie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategorieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Categorie::class);
    }

    public function add(Categorie $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Categorie $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function categoriesSession(SessionFormation $session){

        return $this->createQueryBuilder('c')
        ->from('moduleFormation','mf')
        ->join('mf.programme', 'p')
        ->andWhere('p.sessionFormation = :sess')
        ->setParameter('sess', $session)
        ->groupBy('c.nom_categorie')
        ->getQuery()
        ->getResult();

        // $entityManager = $this->getEntityManager();

        // $query = $entityManager->createQuery(
        //     'SELECT mf.categorie
        //     FROM App\Entity\moduleFormation mf
        //     INNER JOIN mf.programmes p
        //     WHERE p.sessionFormation = :sess
        //     GROUP BY mf.categorie')
        //     ->setParameter('sess', $session);

        // return $query->getResult();

    }

//    /**
//     * @return Categorie[] Returns an array of Categorie objects
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

//    public function findOneBySomeField($value): ?Categorie
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
