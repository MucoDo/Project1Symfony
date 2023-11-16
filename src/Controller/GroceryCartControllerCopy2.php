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
    public function index(SessionInterface $session):response
    {
        $fullPanier = $session->get('panier');
        // dd($fullPanier);
        $vars=['listeCourse'=>$fullPanier];
        return $this -> render('grocery_cart/index.html.twig',$vars);
    }

    #[Route('/grocery/cart/add', name: 'app_grocery_cart_add')]
    public function groceryCartAdd( SessionInterface $session, ManagerRegistry $doctrine, Request $req)
    {
        $panier = $session->get('panier',[]);
        $id=$req->get('id');
        $panier[$id]= 1;
        $session-> set('panier', $panier);
        // dd($session->get('panier'));

        
        $array = $session->get('panier');
        // dd($array);
        $keys = array_keys($array);
        // dd($keys);
        // $liste = implode(", ", $keys);
        // dd($liste);
        $em = $doctrine->getManager();
        $query = $em->createQuery (
            "SELECT i.nom, sum(ir.quantityMeasure) as quantiteTotal FROM App\Entity\Recipe r
            INNER JOIN r.ingredientRecipes ir
            INNER JOIN ir.ingredient i
            WHERE r.id IN (:listeCles)
            GROUP BY i.nom");
        $query->setParameter ('listeCles',$keys);
        $resultats = $query->getResult();
        $vars = ['listeCourse'=> $resultats];
        // dd($vars);
        // REPRENDRE ICI
        $session-> set('panier', $vars);
        // dd($session->get('panier'));
        $session-> save();
        // dd($session);
        return $this -> render('grocery_cart/index.html.twig');
        
    }

    #[Route('/grocery/cart/delete', name: 'app_grocery_cart_delete')]
    public function groceryCartDelete(SessionInterface $session){
        $session->remove('panier');
        // $session-> set('panier', []);  
        $session->save();
        return $this->redirectToRoute('app_grocery_cart');

    }
}
