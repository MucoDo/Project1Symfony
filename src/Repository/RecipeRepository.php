<?php

namespace App\Repository;

use App\Entity\Recipe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Recipe>
 *
 * @method Recipe|null find($id, $lockMode = null, $lockVersion = null)
 * @method Recipe|null findOneBy(array $criteria, array $orderBy = null)
 * @method Recipe[]    findAll()
 * @method Recipe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecipeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Recipe::class);
    }

//    /**
//     * @return Recipe[] Returns an array of Recipe objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Recipe
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
public function showRecipe($id){
    // dd($filtre);
    $em=$this->getEntityManager();
    $query=$em->createQuery(
        "SELECT r FROM App\Entity\Recipe r 
        INNER JOIN r.ingredientRecipes ir
        INNER JOIN ir.ingredient i
        WHERE (r.id= :id or :id is NULL) 
        "
    );

    $query->setParameter("id",$id);
    $res=$query->getResult();

     // dd($res);
    return $res;
}
public function showIngRecipe($id){
    // dd($filtre);
    $em=$this->getEntityManager();
    $query=$em->createQuery(
        "SELECT distinct r.id, i.nom, ir.quantity FROM App\Entity\Recipe r 
        INNER JOIN r.ingredientRecipes ir
        INNER JOIN ir.ingredient i
        WHERE (r.id= :id or :id is NULL) 
        "
    );

    $query->setParameter("id",$id);
    $res=$query->getResult();

     // dd($res);
    return $res;
}

public function groceryList($keys){
    $em =$this->getEntityManager();
    $query = $em->createQuery (
        "SELECT i.nom, sum(ir.quantity) as quantiteTotal FROM App\Entity\Recipe r
        INNER JOIN r.ingredientRecipes ir
        INNER JOIN ir.ingredient i
        WHERE r.id IN (:listeCles)
        GROUP BY i.nom");
    $query->setParameter ('listeCles',$keys);
    $res=$query->getResult();
    return $res;
}
}
