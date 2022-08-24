<?php

namespace App\Repository;

use App\Entity\VehiculeClasse;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<VehiculeClasse>
 *
 * @method VehiculeClasse|null find($id, $lockMode = null, $lockVersion = null)
 * @method VehiculeClasse|null findOneBy(array $criteria, array $orderBy = null)
 * @method VehiculeClasse[]    findAll()
 * @method VehiculeClasse[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VehiculeClasseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VehiculeClasse::class);
    }

    public function add(VehiculeClasse $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(VehiculeClasse $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return VehiculeClasse[] Returns an array of VehiculeClasse objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('v.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?VehiculeClasse
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
