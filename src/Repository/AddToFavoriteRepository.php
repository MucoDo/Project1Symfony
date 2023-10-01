<?php

namespace App\Repository;

use App\Entity\AddToFavorite;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AddToFavorite>
 *
 * @method AddToFavorite|null find($id, $lockMode = null, $lockVersion = null)
 * @method AddToFavorite|null findOneBy(array $criteria, array $orderBy = null)
 * @method AddToFavorite[]    findAll()
 * @method AddToFavorite[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AddToFavoriteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AddToFavorite::class);
    }

//    /**
//     * @return AddToFavorite[] Returns an array of AddToFavorite objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?AddToFavorite
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
