<?php

namespace App\Repository;

use App\Entity\Ingredient;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Ingredient>
 *
 * @method Ingredient|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ingredient|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ingredient[]    findAll()
 * @method Ingredient[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IngredientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ingredient::class);
    }

    // Méthode créée manuellement pour intérroger la bd en fonction de champs de recherche du formulaire
public function searchIngredient($filtre){
    //dd($filtre);
    $em=$this->getEntityManager();
    // $query=$em->createQuery(
    //     "SELECT distinct r.id, r.titre, r.instruction, r.image, i.nom, ir.quantity FROM App\Entity\Recipe r 
    //     INNER JOIN r.ingredientRecipes ir
    //     INNER JOIN ir.ingredient i
    //     WHERE (i.nom LIKE :nom or :nom is NULL) 
    //     "
    // );
    // $query->setParameter("nom","%".$filtre['nom']."%");
    // $res=$query->getResult();
    // return $res;
    $query=$em->createQuery("SELECT distinct r.id FROM App\Entity\Recipe r 
        INNER JOIN r.ingredientRecipes ir
        INNER JOIN ir.ingredient i
        WHERE (i.nom LIKE :nom or :nom is NULL)");
    $query->setParameter("nom","%".$filtre['nom']."%");
    $recipeIds=$query->getResult();
    // dd($recipeIds);
    
    $query2=$em->createQuery("SELECT distinct r.id, r.titre, r.instruction, r.image FROM App\Entity\Recipe r 
        WHERE r.id in (:recipeIds)") ;
    $query2->setParameter('recipeIds', $recipeIds);
    $res=$query2->getResult();
    // dd($res);
    return $res;
}


//    /**
//     * @return Ingredient[] Returns an array of Ingredient objects
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

//    public function findOneBySomeField($value): ?Ingredient
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
