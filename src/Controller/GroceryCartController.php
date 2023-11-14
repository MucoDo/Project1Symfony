<?php

namespace App\Controller;

use App\Repository\IngredientRecipeRepository;
use App\Repository\RecipeRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class GroceryCartController extends AbstractController
{
    #[Route('/grocery/cart', name: 'app_grocery_cart')]
    public function index():response
    {
        // $panier = $session->get('panier',[]);
        return $this -> render('grocery_cart/index.html.twig',[]);
    }

    #[Route('/grocery/cart/add/{id}', name: 'app_grocery_cart_add')]
    public function groceryCartAdd($id, SessionInterface $session, ManagerRegistry $doctrine)
    {
        
        $panier = $session->get('panier',[]);
        $panier[$id]= 1;
        $session-> set('panier', $panier);
        // dd($session->get('panier'));
        
        $array = $session->get('panier');
        $keys = array_keys($array);
        // dd($keys);
        $liste = implode(", ", $keys);
        // dd($liste);
        $em = $doctrine->getManager();
        $query = $em->createQuery (
            "SELECT r.id, i.nom, sum(ir.quantityMeasure) as quantiteTotal FROM App\Entity\Recipe r 
        INNER JOIN r.ingredientRecipes ir
        INNER JOIN ir.ingredient i
        GROUP BY r.id, i.nom
        HAVING r.id IN (:listeCles)");
        $query->setParameter ('listeCles',$keys);
        $resultats = $query->getResult();
        $vars = ['listeCourse'=> $resultats];
        dd($vars);
        // REPRENDRE ICI
        return $this -> render('grocery_cart/index.html.twig',$vars);
    }

}
