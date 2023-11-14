<?php

namespace App\Controller;

use App\Repository\IngredientRecipeRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GroceryCartController extends AbstractController
{
    #[Route('/grocery/cart', name: 'app_grocery_cart')]
    public function index(): Response
    {
        return $this -> render('grocery_cart/index.html.twig',[]);
    }


}
