<?php

namespace App\Repository;

use App\Entity\IngredientRecipe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<IngredientRecipe>
 *
 * @method IngredientRecipe|null find($id, $lockMode = null, $lockVersion = null)
 * @method IngredientRecipe|null findOneBy(array $criteria, array $orderBy = null)
 * @method IngredientRecipe[]    findAll()
 * @method IngredientRecipe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IngredientRecipeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, IngredientRecipe::class);
    }

    public function shoppingList($ids){

        $em=$this->getEntityManager();
    $query=$em->createQuery(
        "SELECT r.id, r.titre, r.instruction, r.image, i.nom, ir.quantityMeasure FROM App\Entity\Recipe r 
        INNER JOIN r.ingredientRecipes ir
        INNER JOIN ir.ingredient i
        WHERE (i.nom LIKE :nom or :nom is NULL) 
        "
    );

    $query->setParameter("nom","%".$ids['nom']."%");
    $res=$query->getResult();

     // dd($res);
    return $res;

    }
//    /**
//     * @return IngredientRecipe[] Returns an array of IngredientRecipe objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('i.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?IngredientRecipe
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
