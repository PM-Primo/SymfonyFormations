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

        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb->select('c')
            ->from('App\Entity\Categorie', 'c')
            ->join('c.moduleFormation', 'mf')
            ->join('mf.programme', 'p')
            ->andWhere('p.sessionFormation = :sess')
            ->setParameter('sess', $session)
            ->groupBy('c.nom_categorie');
        $query = $qb->getQuery();
        return $query->getResult();


        // REQUETE SQL FONCTIONNELLE (TESTEE DANS HEIDI)
        //
        // SELECT c.nom_categorie
        // FROM programme p
        // INNER JOIN module_formation m ON p.module_formation_id = m.id
        // INNER JOIN categorie c ON m.categorie_id = c.id
        // WHERE p.session_formation_id = 7
        // GROUP BY c.nom_categorie

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
