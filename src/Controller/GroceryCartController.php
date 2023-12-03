<?php

namespace App\Controller;

use App\Repository\RecipeRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use App\Repository\IngredientRecipeRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class GroceryCartController extends AbstractController
{
    #[Route('/grocery/cart', name: 'app_grocery_cart')]
    public function index(SessionInterface $session, RecipeRepository $rep,Security $security):response
    {
        if ($security->isGranted('IS_AUTHENTICATED_FULLY')){
        $fullPanier = $session->get('panierAll',[]);
        // dd($fullPanier);
        $ingQuantity=$rep->groceryList($fullPanier);
        // dd($ingQuantity);
        $vars=['listeCourse'=>$ingQuantity];
        // dd($vars);
        return $this -> render('grocery_cart/index.html.twig',$vars);
        } else {
            
         // Récupérer l'URL de connexion ou d'inscription
         $loginUrl = $this->generateUrl('app_login');
         $registerUrl = $this->generateUrl('app_register');
         $vars=['lienConnexion'=>$loginUrl,
         'lienInscr'=>$registerUrl];

        
         return $this->render('recipe_like/recipe_show_likes.html.twig',$vars);
        }
    }

    #[Route('/grocery/cart/add', name: 'app_grocery_cart_add')]
    public function groceryCartAdd( SessionInterface $session, ManagerRegistry $doctrine, Request $req)
    {
        $panier = $session->get('panier',[]);
        $id=$req->get('id');
        $panier[$id]= 1;
        $session-> set('panier', $panier);
        $session-> save();

        // dump($panier);

        
        // $array = $session->get('panier');
        // dd($array);
        $keys = array_keys($panier);
        // dump($keys);
        $session-> set('panierAll',$keys);
        // $liste = implode(", ", $keys);
        // dd($liste);

        //BON CODE//
        /*$em = $doctrine->getManager();
        $query = $em->createQuery (
            "SELECT i.nom, sum(ir.quantityMeasure) as quantiteTotal FROM App\Entity\Recipe r
            INNER JOIN r.ingredientRecipes ir
            INNER JOIN ir.ingredient i
            WHERE r.id IN (:listeCles)
            GROUP BY i.nom");
        $query->setParameter ('listeCles',$keys);
        $resultats = $query->getResult();
        $vars = ['resultats'=> $resultats];
        // dump($vars);
        // REPRENDRE ICI
        $session-> set('panierAll', $vars);
        $session-> save();
        // dd($session->get('panier'));
        // dd($session);*/
        //BON CODE//
        return $this -> render('grocery_cart/index.html.twig');
        
    }

    #[Route('/grocery/cart/delete', name: 'app_grocery_cart_delete')]
    public function groceryCartDelete(SessionInterface $session){
        
        $session->remove('panierAll');
        $session->clear();
        // $session-> set('panier', []);  
        // $session->save();
        return $this->redirectToRoute('app_grocery_cart');

    }
}