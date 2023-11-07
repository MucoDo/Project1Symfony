<?php

namespace App\Controller;

use App\Repository\RecipeRepository;
use App\Repository\IngredientRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AccueilController extends AbstractController
{
    #[Route('/accueil', name: 'accueil')]
    public function index(RecipeRepository $rep): Response
    {
       
            $recettes = $rep->findAll();
            $vars = ['recettes' => $recettes];
            return $this->render('accueil/index.html.twig', $vars);
       

        
    }
}
